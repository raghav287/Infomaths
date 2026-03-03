/**
 * Custom Summernote Buttons and Initialization
 * Extracted from admin/bank-po-exam-tabs-management.php
 */

function initializeCustomSummernote(selector) {
  if (typeof $.fn.summernote === "undefined") {
    console.error("Summernote is not loaded");
    return;
  }

  // Check if the element exists
  if ($(selector).length === 0) {
    return;
  }

  // Define Accordion Button
  var AccordionButton = function (context) {
    var ui = $.summernote.ui;
    var button = ui.button({
      contents: '<i class="fa fa-list-ul"/> Accordion',
      tooltip: "Insert Accordion",
      click: function () {
        var accordionId = "accordion_" + Math.floor(Math.random() * 1000);
        var html = `
                <div class="accordion" id="${accordionId}">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne_${accordionId}">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne_${accordionId}" aria-expanded="true" aria-controls="collapseOne_${accordionId}">
                                <strong style="color: #1C56E1;">Topic Title 1</strong>
                            </button>
                        </h2>
                        <div id="collapseOne_${accordionId}" class="accordion-collapse collapse show" aria-labelledby="headingOne_${accordionId}" data-bs-parent="#${accordionId}">
                            <div class="accordion-body">
                                <p>Enter your content, syllabus, or answer for this topic here.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo_${accordionId}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo_${accordionId}" aria-expanded="false" aria-controls="collapseTwo_${accordionId}">
                                <strong style="color: #1C56E1;">Topic Title 2</strong>
                            </button>
                        </h2>
                        <div id="collapseTwo_${accordionId}" class="accordion-collapse collapse" aria-labelledby="headingTwo_${accordionId}" data-bs-parent="#${accordionId}">
                            <div class="accordion-body">
                                <p>Enter your content, syllabus, or answer for this topic here.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <br>`;
        context.invoke("editor.pasteHTML", html);
      },
    });
    return button.render();
  };

  // Define Alert/Callout Button
  var CalloutButton = function (context) {
    var ui = $.summernote.ui;
    var button = ui.button({
      contents: '<i class="fa fa-info-circle"/> Info Box',
      tooltip: "Insert Information Box",
      click: function () {
        var html = `
                <div class="alert alert-success mt-4">
                    <strong>Important Note:</strong> <a href="#" class="alert-link">Click here</a> for more details or enter your text here.
                </div>
                <br>`;
        context.invoke("editor.pasteHTML", html);
      },
    });
    return button.render();
  };

  // Define 2-Column Layout Button
  var TwoColButton = function (context) {
    var ui = $.summernote.ui;
    var button = ui.button({
      contents: '<span style="font-weight:bold;">2-Col</span>',
      tooltip: "Insert 2 Columns",
      click: function () {
        var html = `
                <div class="mb-4">
                    <h4 style="color: #1C56E1; margin-bottom: 20px; font-weight: 700;">Sample Title Here</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <li>Sample list item 1</li>
                                <li>Sample list item 2</li>
                                <li>Sample list item 3</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <li>Sample list item 1</li>
                                <li>Sample list item 2</li>
                                <li>Sample list item 3</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <br>`;
        context.invoke("editor.pasteHTML", html);
      },
    });
    return button.render();
  };

  // Define 3-Column Layout Button
  var ThreeColButton = function (context) {
    var ui = $.summernote.ui;
    var button = ui.button({
      contents: '<span style="font-weight:bold;">3-Col</span>',
      tooltip: "Insert 3 Columns",
      click: function () {
        var html = `
                <div class="mb-4">
                    <h4 style="color: #1C56E1; margin-bottom: 20px; font-weight: 700;">Sample Title Here</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <li>Sample list item 1</li>
                                <li>Sample list item 2</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <li>Sample list item 1</li>
                                <li>Sample list item 2</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <li>Sample list item 1</li>
                                <li>Sample list item 2</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <br>`;
        context.invoke("editor.pasteHTML", html);
      },
    });
    return button.render();
  };

  // Define 4-Column Layout Button
  var FourColButton = function (context) {
    var ui = $.summernote.ui;
    var button = ui.button({
      contents: '<span style="font-weight:bold;">4-Col</span>',
      tooltip: "Insert 4 Columns",
      click: function () {
        var html = `
                <div class="mb-4">
                    <h4 style="color: #1C56E1; margin-bottom: 20px; font-weight: 700;">Sample Title Here</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <li>Sample list item 1</li>
                                <li>Sample list item 2</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <li>Sample list item 1</li>
                                <li>Sample list item 2</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <li>Sample list item 1</li>
                                <li>Sample list item 2</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <li>Sample list item 1</li>
                                <li>Sample list item 2</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <br>`;
        context.invoke("editor.pasteHTML", html);
      },
    });
    return button.render();
  };

  // Initialize Summernote with custom buttons
  $(selector).summernote({
    placeholder: "Enter content...",
    tabsize: 2,
    height: 300,
    dialogsInBody: true,
    tableClassName:
      "table table-bordered table-striped text-center align-middle dynamic-styled-table",
    toolbar: [
      ["style", ["style"]],
      ["font", ["bold", "underline", "clear"]],
      ["fontsize", ["fontsize"]],
      ["color", ["color"]],
      ["para", ["ul", "ol", "paragraph"]],
      ["table", ["table"]],
      ["insert", ["link", "picture", "video"]],
      ["view", ["fullscreen", "codeview", "help"]],
      ["custom", ["accordion", "callout"]],
      ["layout", ["twoCol", "threeCol", "fourCol"]],
    ],
    buttons: {
      accordion: AccordionButton,
      callout: CalloutButton,
      twoCol: TwoColButton,
      threeCol: ThreeColButton,
      fourCol: FourColButton,
    },
  });
}
