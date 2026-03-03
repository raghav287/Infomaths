    <style>
        @font-face {
            font-family: 'TT Firs Text Trial Italic';
            src: url('assets/fonts/TT Firs Text Trial Light.ttf') format('truetype');
            font-weight: 400;
            font-style: Light;
        }

        /* NIMCET Results Slider Card Visual Improvements */
        .nimcet-slider {
            margin: 0 auto;
            max-width: 1200px;
        }

        .nimcet-slider .slider {
            display: flex;
            align-items: stretch;
        }

        .slide-card {
            background: linear-gradient(135deg, #232526 0%, #414345 100%);
            color: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.18);
            padding: 32px 24px 24px 24px;
            margin: 0 12px;
            text-align: center;
            position: relative;
            min-width: 320px;
            max-width: 350px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .slide-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.22);
        }

        .slide-header {
            font-size: 2rem;
            font-weight: 700;
            color: #ffd700;
            margin-bottom: 8px;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
        }

        .slide-subtitle {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 10px;
            color: #fff;
            opacity: 0.85;
        }

        .slide-logo {
            display: block;
            margin: 0 auto 10px auto;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
            padding: 6px;
        }

        .slide-rank {
            font-size: 2.2rem;
            font-weight: 800;
            background: #000;
            color: #ffd700;
            border-radius: 8px;
            padding: 8px 24px;
            margin-bottom: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
            letter-spacing: 2px;
            display: inline-block;
        }

        .slide-appno {
            font-size: 1rem;
            color: #ccc;
            margin-bottom: 8px;
            font-family: 'Euclid Circular A', Arial, sans-serif;
        }

        .slide-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 1px;
            margin-bottom: 0;
            text-transform: uppercase;
        }

        /* Slick Dots and Arrows Position & Style Fix */
        .nimcet-slider {
            position: relative;
            padding-bottom: 60px;
        }

        .nimcet-slider .slick-dots {
            position: absolute !important;
            left: 50%;
            transform: translateX(-50%);
            bottom: 10px;
            display: flex !important;
            justify-content: center;
            gap: 8px;
            margin: 0;
            padding: 0;
        }

        .nimcet-slider .slick-dots li {
            margin: 0 4px;
        }

        .nimcet-slider .slick-dots li button {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #ffd700;
            border: none;
            color: transparent;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
            transition: background 0.2s, transform 0.2s;
        }

        .nimcet-slider .slick-dots li.slick-active button {
            background: #232526;
            border: 2px solid #ffd700;
            transform: scale(1.2);
        }

        .nimcet-slider .slick-dots li button:before {
            display: none;
        }

        .nimcet-slider .slick-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #ffd700;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            z-index: 2;
            border: none;
            color: #232526;
            font-size: 22px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
            transition: background 0.2s, color 0.2s;
        }

        .nimcet-slider .slick-arrow.slick-prev {
            left: -20px;
        }

        .nimcet-slider .slick-arrow.slick-next {
            right: -20px;
        }

        .nimcet-slider .slick-arrow:hover {
            background: #232526;
            color: #ffd700;
        }

        /* Testimonials Slider Arrows */
        /* .td_slider .slick-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            z-index: 2;
            border: none;
            color: #1e4e64;
            font-size: 18px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        } */

        /* .td_slider .slick-arrow:hover {
            background: #1e4e64;
            color: #fff;
            transform: translateY(-50%) scale(1.1);
        } */

        .td_slider .slick-prev {
            left: -20px;
        }

        .td_slider .slick-next {
            right: -20px;
        }

        @media (max-width: 768px) {
            .td_slider .slick-arrow {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }

            .td_slider .slick-prev {
                left: -15px;
            }

            .td_slider .slick-next {
                right: -15px;
            }

            .photo_grid {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
            }
        }
    </style>

    <style>
        /* 1. Define the rotation animation */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* 2. Apply the animation when hovering over the main box */
        .td_college:hover .td_college_icon img {
            animation: spin 4s linear infinite;
        }

        /*Start Program Card*/
        .program-card {
            position: relative;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            padding: 40px 15px;
            cursor: pointer;
            transform-style: preserve-3d;
            perspective: 1000px;
            will-change: transform;
            transition: transform 0.2s ease;
            /* reset animation on mouseleave */
        }

        /* Background image */
        .program-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: var(--bg-image);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(0.5px);
            transform: scale(1.1);
            opacity: 0;
            transition: opacity 0.6s ease, transform 0.8s ease;
            z-index: 0;
        }

        /* Overlay */
        .program-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background-color: #1e4e64;
            opacity: 0;
            transition: opacity 0.6s ease;
            z-index: 0;
        }

        .program-card:hover::before {
            opacity: 1;
            transform: scale(1.2);
        }

        .program-card:hover::after {
            opacity: 0.6;
        }

        .program-card-content {
            position: relative;
            z-index: 1;
            transition: color 0.4s ease, transform 0.4s ease;
            will-change: transform;
        }

        .program-card-icon img {
            width: 35px;
            margin-bottom: 15px;
            transition: filter 0.4s ease;
        }

        .program-card-title {
            font-size: 20px;
            font-weight: 600;
            color: #222;
            transition: color 0.4s ease;
        }

        .program-card:hover .program-card-icon img {
            filter: brightness(0) invert(1);
        }

        .program-card:hover .program-card-title {
            color: #fff;
        }

        .program-card-link {
            position: absolute;
            inset: 0;
            z-index: 2;
        }


        /*End Program Card*/
        @media (max-width: 991px) {
            .td_top_bar_right {
                gap: 9px !important;
            }
        }

        /* Header Transparency Effect */
        /* .td_site_header {
            background-color: transparent !important;
            transition: all 0.4s ease;
        }

        .td_site_header .td_main_header {
            background-color: transparent !important;
            transition: all 0.4s ease;
        } */

        /* .td_site_header.td_gescout_sticky,
        .td_site_header.td_sticky_active {
            background-color: #1e4e64 !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .td_site_header.td_gescout_sticky .td_main_header,
        .td_site_header.td_sticky_active .td_main_header {
            background-color: #1e4e64 !important;
        } */

        /* Ensure text remains visible on blue background */
        /* Programs Mega Menu Font Size */
        .td_mega_wrapper a {
            font-size: 12px !important;
        }

        .td_mega_wrapper h4 {
            font-size: 14px !important;
            font-weight: 600;
        }

        @media screen and (min-width: 1100px) {
            .td_nav .td_nav_list .td_mega_wrapper {
                width: 1196px !important;
                display: flex !important;
                position: fixed;
                border-radius: 7px;
                top: 100px !important;
                left: 50%;
                line-height: 1.2em;
                transform: translateX(-50%);
                padding: 5px 15px 10px;
                border: none;


            }
        }

        @media screen and (min-width: 1200px) {
            .td_nav .td_nav_list .td_mega_wrapper {
                width: 1196px !important;
                display: flex !important;
                position: fixed;
                border-radius: 7px;
                top: 100px !important;
                line-height: 1.2em;
                left: 50%;
                transform: translateX(-50%);
                padding: 5px 15px 10px;
                border: none;

            }
        }

        @media screen and (min-width: 1400px) {
            .td_nav .td_nav_list .td_mega_wrapper {
                width: 1196px !important;
                display: flex !important;
                border: none;
                line-height: 1.2em;
                position: fixed;
                top: 100px !important;
                left: 50%;
                transform: translateX(-50%);
                padding: 5px 15px 10px;

            }
        }


        /* Placement Records Slider Styles */
        .placement-records-slider-container {
            position: relative;
            overflow: hidden;
        }

        .placement-record-card {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;

        }

        .placement-record-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .placement-image-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .placement-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .placement-record-card:hover .placement-image {
            transform: scale(1.05);
        }

        .placement-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            padding: 40px 20px 20px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .placement-record-card:hover .placement-overlay {
            transform: translateY(0);
        }

        .placement-info {
            color: white;
            text-align: center;
        }

        .placement-info .student-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #fff;
        }

        .placement-info .company-name {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: #ffd700;
        }

        .placement-info .package {
            font-size: 1.1rem;
            font-weight: 700;
            color: #00ff88;
            margin-bottom: 0;
        }

        .placement-slider-navigation {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .placement-nav-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #1e4e64;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(30, 78, 100, 0.3);
        }

        .placement-nav-btn:hover {
            background: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(30, 78, 100, 0.4);
        }

        .placement-nav-btn svg {
            width: 24px;
            height: 24px;
        }

        /* Badge styling for placement record cards */


        /* Responsive adjustments */
        @media (max-width: 768px) {
            .placement-record-card {
                height: 300px;
            }

            .placement-info .student-name {
                font-size: 1.1rem;
            }

            .placement-info .company-name {
                font-size: 0.9rem;
            }

            .placement-info .package {
                font-size: 1rem;
            }

            .placement-nav-btn {
                width: 45px;
                height: 45px;
            }
        }

        /* Animation for slider entrance */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .placement-records-slider-container.wow.fadeInUp {
            animation: slideInUp 0.6s ease-out;
        }

        /* Top Recruiters Slider Styles */
        .top-recruiters-slider {
            overflow: hidden;
        }

        /* ChatGPT Style Search Modal Styles */
        .search-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .search-modal.active {
            display: flex;
            opacity: 1;
        }

        .search-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
        }

        .search-modal-content {
            position: relative;
            width: 90%;

            margin: 5% auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            animation: modalSlideIn 0.3s ease-out;

            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .search-modal-header {
            display: flex;
            justify-content: flex-end;
            padding: 16px 20px 0;
        }

        .search-modal-close {
            background: none;
            border: none;
            padding: 8px;
            cursor: pointer;
            border-radius: 8px;
            color: #6b7280;
            transition: all 0.2s ease;
        }

        .search-modal-close:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .search-modal-body {
            padding: 0 24px 24px;
            flex: 1;
            overflow-y: auto;
        }

        .search-input-container {
            position: relative;
            display: flex;
            align-items: center;
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 0 16px;
            transition: all 0.2s ease;
            margin-bottom: 24px;
        }

        .search-input-container:focus-within {
            border-color: #1e4e64;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(30, 78, 100, 0.1);
        }

        .search-icon {
            color: #9ca3af;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .search-modal-input {
            flex: 1;
            border: none;
            background: transparent;
            outline: none;
            padding: 16px 0;
            font-size: 16px;
            color: #111827;
            font-family: inherit;
        }

        .search-modal-input::placeholder {
            color: #9ca3af;
        }

        .search-submit-btn {
            background: #1e4e64;
            border: none;
            border-radius: 8px;
            padding: 8px;
            color: white;
            cursor: pointer;
            transition: background 0.2s ease;
            margin-left: 8px;
            flex-shrink: 0;
        }

        .search-submit-btn:hover {
            background: #0056b3;
        }

        .search-suggestions {
            margin-bottom: 24px;
        }

        .suggestions-header {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .suggestion-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .suggestion-pill {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 14px;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .suggestion-pill:hover {
            background: #1e4e64;
            color: white;
            border-color: #1e4e64;
        }

        /* AI Summary Styles */
        .search-summary {
            margin-bottom: 24px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #0ea5e9;
            border-radius: 12px;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .search-summary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #0ea5e9, #3b82f6, #8b5cf6);
            animation: shimmer 2s linear infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .summary-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .summary-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #0f172a;
            font-size: 16px;
        }

        .summary-title svg {
            color: #0ea5e9;
        }

        .summary-regenerate {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #64748b;
        }

        .summary-regenerate:hover {
            background: #f1f5f9;
            color: #334155;
            border-color: #cbd5e1;
        }

        .summary-content {
            font-size: 15px;
            line-height: 1.6;
            color: #334155;
            margin-bottom: 16px;
            min-height: 60px;
        }

        .summary-content.loading {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #64748b;
            font-style: italic;
        }

        .summary-content.loading::before {
            content: '';
            width: 16px;
            height: 16px;
            border: 2px solid #e2e8f0;
            border-top: 2px solid #0ea5e9;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .summary-sources {
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
        }

        .sources-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sources-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .source-link {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 4px 12px;
            font-size: 12px;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .source-link:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #334155;
        }

        .source-link::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #22c55e;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .search-results {
            max-height: 400px;
            overflow-y: auto;
        }

        .results-header {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .results-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .search-result-item {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-result-item:hover {
            border-color: #1e4e64;
            box-shadow: 0 4px 12px rgba(30, 78, 100, 0.1);
        }

        .result-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e4e64;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .result-snippet {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 8px;
        }

        .result-url {
            font-size: 12px;
            color: #059669;
            text-decoration: none;
        }

        .search-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            color: #6b7280;
        }

        .loading-spinner {
            width: 32px;
            height: 32px;
            border: 3px solid #e5e7eb;
            border-top: 3px solid #1e4e64;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 12px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-modal-content {
                width: 95%;
                margin: 2% auto;
                max-height: 95vh;
            }

            .search-modal-body {
                padding: 0 16px 16px;
            }

            .search-input-container {
                padding: 0 12px;
            }

            .search-modal-input {
                padding: 14px 0;
                font-size: 16px;
            }

            .suggestion-pills {
                gap: 6px;
            }

            .suggestion-pill {
                padding: 6px 12px;
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .search-modal-content {
                width: 98%;
                margin: 1% auto;
            }
        }

        .marquee-slider-image {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 80px;
            width: 100px;
            padding: 15px;
            border-radius: 10px;
            margin: 0 10px;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .marquee-slider-image:hover {

            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .marquee-slider-image img {
            max-height: 100px;
            max-width: 110px;
            object-fit: contain;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .marquee-slider-image:hover img {
            transform: scale(1.1);
        }

        /* Responsive adjustments for recruiters slider */
        @media (max-width: 768px) {
            .marquee-slider-image {
                height: 60px;
                width: 100px;
                padding: 10px;
                margin: 0 5px;
            }

            .marquee-slider-image img {
                max-height: 35px;
                max-width: 80px;
            }
        }


        /*App section*/
        .app-section_custom {
            min-height: 500px;
            padding: 40px 20px;
            background: #00001b;
            /* Background */
        }

        .app-container_custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .td_cta_text_custom {
            flex: 1 1 50%;
            color: #fff;
            max-width: 600px;
        }

        .td_section_subtitle_custom {
            font-size: 16px;
            text-transform: uppercase;
            opacity: 0.8;
            margin-bottom: 10px;
        }

        .td_section_title_custom {
            color: #fff;
            /* FIXED: white heading */
            font-size: 42px;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .td_btns_group_custom {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .td_btn_custom {
            display: inline-flex;
            align-items: center;
            padding: 12px 20px;
            border-radius: 30px;
            background: #fff;
            color: #0a3c75;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
        }

        .td_btn_custom:hover {
            background: #1e4e64;
            color: #fff;
        }

        .td_btn_custom svg {
            margin-right: 8px;
        }

        /* FIXED: match HTML class name */
        .td_cta_thumb {
            flex: 1 1 45%;
            text-align: center;
        }

        .td_cta_thumb img {
            max-width: 100%;
            height: auto;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .td_section_title_custom {
                font-size: 32px;
            }
        }

        @media (max-width: 768px) {
            .app-container_custom {
                flex-direction: column;
                text-align: center;
            }

            .td_cta_text_custom,
            .td_cta_thumb {
                flex: 1 1 100%;
                max-width: 100%;
            }

            .td_btns_group_custom {
                justify-content: center;
            }

            .td_section_title_custom {
                font-size: 28px;
            }
        }

        /* Notification Bell Styles */
        .td_notification_btn {
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* Notification Modal Styles */
        .notification-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            backdrop-filter: blur(8px);
        }

        .notification-modal.active {
            display: flex;
            opacity: 1;
            align-items: center;
            justify-content: center;
        }

        .notification-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }

        .notification-modal-content {
            position: relative;
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 1200px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            transform: scale(0.95);
            transition: transform 0.3s ease-in-out;
            z-index: 10001;
        }

        .notification-modal.active .notification-modal-content {
            transform: scale(1);
        }

        .notification-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            background: #f8f9fa;
        }

        .notification-modal-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
        }

        .notification-modal-close {
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .notification-modal-close:hover {
            background: #e5e7eb;
            color: #374151;
        }

        .notification-modal-body {
            padding: 0;
            max-height: 60vh;
            overflow-y: auto;
        }

        .notification-filters {
            padding: 20px 24px 0;
            border-bottom: 1px solid #e5e7eb;
            background: white;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .filter-tab {
            padding: 8px 16px;
            border: 1px solid #d1d5db;
            background: white;
            color: #6b7280;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .filter-tab:hover {
            border-color: #1e4e64;
            color: #1e4e64;
        }

        .filter-tab.active {
            background: #1e4e64;
            color: white;
            border-color: #1e4e64;
        }

        .filter-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #dc2626;
            color: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        }

        .filter-badge:empty {
            display: none;
        }

        .filter-tab {
            position: relative;
        }

        .notifications-container {
            padding: 16px 0;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 16px 24px;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s ease;
            position: relative;
        }

        .notification-item:hover {
            background: #f8f9fa;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-icon.engineering {
            background: #e7f3ff;
            color: #0066cc;
        }

        .notification-icon.pharmacy {
            background: #f0fff4;
            color: #16a34a;
        }

        .notification-icon.management {
            background: #fef7e7;
            color: #d97706;
        }

        .notification-icon.computer {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .notification-icon.hotel {
            background: #fef2f2;
            color: #dc2626;
        }

        .notification-icon.dsw {
            background: #e0f2fe;
            color: #0277bd;
        }

        .notification-icon.rise {
            background: #fff8e1;
            color: #f57c00;
        }

        .notification-content {
            flex: 1;
        }

        .notification-content h4 {
            margin: 0 0 8px 0;
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
        }

        .notification-content p {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
        }

        .notification-time {
            font-size: 12px;
            color: #9ca3af;
        }

        .notification-status {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .notification-status.unread {
            background: #dc3545;
        }

        .notification-status.read {
            background: #e5e7eb;
        }

        /* Responsive Design for Notification Modal */
        @media (max-width: 768px) {
            .notification-modal-content {
                width: 95%;
                margin: 2% auto;
                max-height: 90vh;
            }

            .notification-modal-header {
                padding: 16px 20px;
            }

            .notification-filters {
                padding: 16px 20px 0;
            }

            .filter-tabs {
                gap: 6px;
            }

            .filter-tab {
                padding: 6px 12px;
                font-size: 13px;
            }

            .notification-item {
                padding: 12px 20px;
                gap: 12px;
            }

            .notification-icon {
                width: 36px;
                height: 36px;
            }

            .notification-content h4 {
                font-size: 15px;
            }

            .notification-content p {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .notification-modal-content {
                width: 98%;
                margin: 1% auto;
            }

            .filter-tabs {
                justify-content: flex-start;
            }

            .filter-tab {
                padding: 5px 10px;
                font-size: 12px;
            }
        }

        .notice-container {
            max-width: 500px;
            margin: auto;
            border: 1px solid #ddd;
            background: #fff;
        }

        .notice-title {
            text-align: center;
            font-weight: bold;
            padding: 10px;
            font-size: 18px;
        }

        .notice-box {
            height: 300px;
            overflow: hidden;
            position: relative;
            padding: 10px;
        }

        /* Animation */
        .notice-vertical {
            list-style: none;
            padding: 0;
            margin: 0;
            position: absolute;
            width: 100%;
            animation: scrollUp 20s linear infinite;
        }

        /* Pause when hovered */
        .notice-box:hover .notice-vertical {
            animation-play-state: paused;
        }

        .notice-vertical li {
            padding: 15px 0 5px 0;
            border-bottom: 1px solid rgb(219, 219, 219);
        }

        .notice-vertical a {

            color: #007bff;
        }

        @keyframes scrollUp {
            from {
                top: 100%;
            }

            to {
                top: -200%;
            }
        }

        .course_profile_card {
            padding: 30px 20px;
            border: 1px solid rgb(235, 235, 235);
            border-radius: 5px;
            background: white;
        }

        .course_profile_card {
            transition: all 0.3s ease;
        }

        .course_profile_card:hover {

            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.15);
        }
    </style>
