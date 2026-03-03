// Wrapper to avoid global scope pollution and basic double-init check
(function () {
  if (window.headerModalsInitialized) return;
  window.headerModalsInitialized = true;

  // ChatGPT Style Search Modal JavaScript
  $(document).ready(function () {
    const API_KEY = "AIzaSyCsRaU-hQ2edPeMf4tE6rgP1to15BH4RcY";
    const SEARCH_ENGINE_ID = "67ea2c4ed606f4ea5";
    const API_URL = `https://www.googleapis.com/customsearch/v1?key=${API_KEY}&cx=${SEARCH_ENGINE_ID}`;

    // Convert Markdown bold (**text**) to HTML bold tags
    function markdownToHtml(text) {
      let html = text.replace(/\*\*(.*?)\*\*/g, "<b>$1</b>");
      let bulletRegex = /^\s*\*\s+(.*)$/gm;
      html = html.replace(bulletRegex, "<li>$1</li>");
      if (/<li>/.test(html)) {
        html = '<ul style="margin:0 0 1em 1.2em; padding:0;">' + html + "</ul>";
      }
      return html;
    }

    // Modal controls
    const searchModal = $("#searchModal");
    const searchModalTrigger = $("#searchModalTrigger");
    const closeSearchModal = $("#closeSearchModal");
    const modalSearchInput = $("#modalSearchInput");
    const searchResults = $("#searchResults");
    const searchLoading = $("#searchLoading");
    const resultsContainer = $("#resultsContainer");
    const resultsCount = $("#resultsCount");
    const submitSearchBtn = $("#submitSearch");
    const searchSummary = $("#searchSummary");
    const summaryContent = $("#summaryContent");
    const sourcesList = $("#sourcesList");
    const regenerateSummary = $("#regenerateSummary");

    let originalBodyOverflow = $("body").css("overflow");

    searchModalTrigger.on("click", function (e) {
      e.preventDefault();
      originalBodyOverflow = $("body").css("overflow");
      searchModal.addClass("active");
      modalSearchInput.focus();
      $("body").css("overflow", "hidden");
    });

    closeSearchModal.on("click", function () {
      closeModal();
    });

    $(".search-modal-overlay").on("click", function () {
      closeModal();
    });

    $(document).on("keydown", function (e) {
      if (e.key === "Escape" && searchModal.hasClass("active")) {
        closeModal();
      }
    });

    function closeModal() {
      searchModal.removeClass("active");
      if (originalBodyOverflow && originalBodyOverflow !== "visible") {
        $("body").css("overflow", originalBodyOverflow);
      } else {
        $("body").css("overflow", "");
      }
      modalSearchInput.val("");
      searchResults.hide();
      searchSummary.hide();
      $(".search-suggestions").show();
    }

    $(".suggestion-pill").on("click", function () {
      const query = $(this).data("query");
      modalSearchInput.val(query);
      performSearch(query);
    });

    modalSearchInput.on("keypress", function (e) {
      if (e.which === 13) {
        e.preventDefault();
        const query = $(this).val().trim();
        if (query) {
          performSearch(query);
        }
      }
    });

    submitSearchBtn.on("click", function (e) {
      e.preventDefault();
      const query = modalSearchInput.val().trim();
      if (query) {
        performSearch(query);
      }
    });

    async function performSearch(query) {
      if (!query.trim()) return;
      searchLoading.show();
      searchResults.hide();
      searchSummary.hide();
      $(".search-suggestions").hide();

      try {
        const response = await fetch(
          `${API_URL}&q=${encodeURIComponent(query)}&num=10`
        );
        if (!response.ok)
          throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        searchLoading.hide();

        if (data.items && data.items.length > 0) {
          displayResults(data.items, query);
          await generateAISummary(query, data.items);
        } else {
          displayNoResults(query);
        }
      } catch (error) {
        console.error("Search error:", error);
        searchLoading.hide();
        displayError();
      }
    }

    async function generateAISummary(query, searchItems) {
      try {
        searchSummary.show();
        summaryContent.addClass("loading").html("Generating AI summary...");
        const allResults = searchItems.map((item) => ({
          title: item.title,
          snippet: item.snippet,
          link: item.link,
          displayLink: item.displayLink,
        }));
        let summary = "";
        try {
          summary = await generateOpenAISummary(query, allResults);
        } catch (error) {
          displaySummaryError();
          return;
        }
        displaySummary(summary, allResults, query);
      } catch (error) {
        displaySummaryError();
      }
    }

    async function generateOpenAISummary(query, searchContent) {
      const endpoint = "index.php?gemini_proxy=1";
      const prompt =
        `You are an expert education assistant. Summarize the following search results for the query: "${query}".\n\n` +
        "Please write a professional, well-structured summary in 3-5 bullet points, using proper grammar and formatting.\n" +
        searchContent
          .map(
            (item, i) =>
              `${i + 1}. Title: ${item.title}\nSnippet: ${item.snippet}`
          )
          .join("\n\n");

      const body = { contents: prompt };
      const response = await fetch(endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(body),
      });

      if (!response.ok) throw new Error("Gemini API error");
      const data = await response.json();
      return (
        data.candidates?.[0]?.content?.parts?.[0]?.text ||
        "No summary generated."
      );
    }

    function displaySummary(summary, searchContent, query) {
      summaryContent
        .removeClass("loading")
        .html(markdownToHtml(summary).replace(/\n/g, "<br>"));
      const sources = searchContent
        .slice(0, 5)
        .map((item) => {
          return `<a href="${item.link}" target="_blank" class="source-link" title="${item.title}">${item.displayLink}</a>`;
        })
        .join("");
      sourcesList.html(sources);
    }

    function displaySummaryError() {
      summaryContent
        .removeClass("loading")
        .html(
          `<div style="color: #ef4444; font-style: italic;">Unable to generate summary at this time.</div>`
        );
    }

    regenerateSummary.on("click", async function () {
      const query = modalSearchInput.val().trim();
      if (query) {
        const searchResultItems = $("#resultsContainer .search-result-item");
        if (searchResultItems.length > 0) {
          summaryContent.addClass("loading").html("Regenerating summary...");
          const mockItems = Array.from(searchResultItems).map((item) => ({
            title: $(item).find(".result-title").text(),
            snippet: $(item).find(".result-snippet").text(),
            link: $(item)
              .attr("onclick")
              .match(/'([^']+)'/)[1],
            displayLink: $(item).find(".result-url").text(),
          }));
          try {
            const newSummary = await generateOpenAISummary(query, mockItems);
            displaySummary(newSummary, mockItems, query);
          } catch (error) {
            displaySummaryError();
          }
        }
      }
    });

    function displayResults(items, query) {
      resultsContainer.empty();
      resultsCount.text(`Found ${items.length} results for "${query}"`);
      items.forEach((item) => {
        resultsContainer.append(createResultItem(item));
      });
      searchResults.show();
    }

    function createResultItem(item) {
      const title = item.title || "Untitled";
      const snippet = item.snippet || "No description available";
      const link = item.link || "#";
      const displayLink = item.displayLink || new URL(link).hostname;
      return `
            <div class="search-result-item" onclick="window.open('${link}', '_blank')">
              <div class="result-title">${escapeHtml(title)}</div>
              <div class="result-snippet">${escapeHtml(snippet)}</div>
              <div class="result-url">${escapeHtml(displayLink)}</div>
            </div>`;
    }

    function displayNoResults(query) {
      resultsContainer.html(
        `<div style="text-align: center; padding: 40px; color: #6b7280;">No results found</div>`
      );
      resultsCount.text(`No results found for "${query}"`);
      searchResults.show();
    }

    function displayError() {
      resultsContainer.html(
        `<div style="text-align: center; padding: 40px; color: #ef4444;">Search Error</div>`
      );
      resultsCount.text("Search Error");
      searchResults.show();
    }

    function escapeHtml(unsafe) {
      return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }

    searchModal.on("transitionend", function () {
      if (searchModal.hasClass("active")) {
        modalSearchInput.focus();
      }
    });

    let searchTimeout;
    modalSearchInput.on("input", function () {
      const query = $(this).val().trim();
      clearTimeout(searchTimeout);
      if (query.length >= 3) {
        searchTimeout = setTimeout(() => {
          // performSearch(query);
        }, 500);
      }
    });
  });

  // Notification Modal functionality
  $(document).ready(function () {
    const notificationModal = $("#notificationModal");
    const notificationModalTrigger = $("#notificationModalTrigger");
    const closeNotificationModal = $("#closeNotificationModal");
    const filterTabs = $(".filter-tab");
    const notificationItems = $(".notification-item");
    let notificationBodyOverflow = $("body").css("overflow");

    function setCookie(name, value, days) {
      let expires = "";
      if (days) {
        const date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
      const nameEQ = name + "=";
      const ca = document.cookie.split(";");
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === " ") c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0)
          return c.substring(nameEQ.length, c.length);
      }
      return null;
    }

    function deleteCookie(name) {
      document.cookie =
        name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }

    function saveNotificationStates() {
      const states = {};
      notificationItems.each(function () {
        const notificationId = $(this).data("id");
        const isRead = $(this).find(".notification-status").hasClass("read");
        if (notificationId) {
          states[notificationId] = isRead;
        }
      });
      setCookie("notificationStates", JSON.stringify(states), 30);
    }

    function loadNotificationStates() {
      const savedStates = getCookie("notificationStates");
      if (savedStates) {
        try {
          const states = JSON.parse(savedStates);
          notificationItems.each(function () {
            const notificationId = $(this).data("id");
            const status = $(this).find(".notification-status");
            if (notificationId && states.hasOwnProperty(notificationId)) {
              if (states[notificationId] === true) {
                status.removeClass("unread").addClass("read");
              } else {
                status.removeClass("read").addClass("unread");
              }
            }
          });
        } catch (e) {
          deleteCookie("notificationStates");
        }
      }
    }

    notificationModalTrigger.on("click", function (e) {
      e.preventDefault();
      notificationBodyOverflow = $("body").css("overflow");
      notificationModal.addClass("active");
      $("body").css("overflow", "hidden");
    });

    closeNotificationModal.on("click", function () {
      closeNotificationModalFunc();
    });

    $(".notification-modal-overlay").on("click", function () {
      closeNotificationModalFunc();
    });

    $(document).on("keydown", function (e) {
      if (e.key === "Escape" && notificationModal.hasClass("active")) {
        closeNotificationModalFunc();
      }
    });

    function closeNotificationModalFunc() {
      notificationModal.removeClass("active");
      $("body").css("overflow", notificationBodyOverflow);
    }

    filterTabs.on("click", function () {
      const selectedCategory = $(this).data("category");
      filterTabs.removeClass("active");
      $(this).addClass("active");
      notificationItems.each(function () {
        const itemCategory = $(this).data("category");
        if (selectedCategory === "all" || itemCategory === selectedCategory) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });

    notificationItems.on("click", function () {
      const status = $(this).find(".notification-status");
      if (status.hasClass("unread")) {
        status.removeClass("unread").addClass("read");
        saveNotificationStates();
        updateNotificationBadge();
      }
    });

    function updateNotificationBadge() {
      const unreadCount = $(".notification-status.unread").length;
      const badge = $(".notification-badge");
      if (unreadCount > 0) {
        badge.text(unreadCount).show();
      } else {
        badge.hide();
      }
      updateFilterBadges();
    }

    function updateFilterBadges() {
      const categories = ["all"];
      filterTabs.each(function () {
        const category = $(this).data("category");
        if (category !== "all" && !categories.includes(category))
          categories.push(category);
      });
      categories.forEach(function (category) {
        let count = 0;
        if (category === "all") {
          count = $(".notification-status.unread").length;
        } else {
          $(".notification-item").each(function () {
            const itemCategory = $(this).data("category");
            const status = $(this).find(".notification-status");
            if (itemCategory === category && status.hasClass("unread")) count++;
          });
        }
        const filterBadge = $("#badge-" + category);
        if (count > 0) filterBadge.text(count).show();
        else filterBadge.hide();
      });
    }

    function initializeNotifications() {
      loadNotificationStates();
      updateNotificationBadge();
      window.clearNotificationCookies = function () {
        deleteCookie("notificationStates");
        location.reload();
      };
    }

    initializeNotifications();
  });
})();
