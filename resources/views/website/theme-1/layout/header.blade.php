<header id="header" data-transparent="true" data-fullwidth="true" class="dark submenu-light">
    <div class="header-inner">
        <div class="container">
            <!--Logo-->
            <div id="logo">
                <a href="{{ url('/') }}">
                    <span class="logo-default">IVAO CO</span>
                    <span class="logo-dark">IVAO CO</span>
                </a>
            </div>
            <!--End: Logo-->
            {{-- <!-- Search -->
            <div id="search"><a id="btn-search-close" class="btn-search-close" aria-label="Close search form"><i
                        class="icon-x"></i></a>
                <form class="search-form" action="search-results-page.html" method="get">
                    <input class="form-control" name="q" type="text" placeholder="Type & Search..." />
                    <span class="text-muted">Start typing & press "Enter" or "ESC" to close</span>
                </form>
            </div>
            <!-- end: search --> --}}
            <!--Header Extras-->
            <div class="header-extras">
                <ul>

                    <li class="d-none d-xl-block d-lg-block ml-2">
                        <a href="{{ url('/') }}" class="btn btn-rounded">Ingresar</a>
                    </li>

                    <li class="d-none d-xl-block d-lg-block ml-2">
                        <a href="{{ url('/') }}" class="btn btn-rounded">Registrate</a>
                    </li>

                    {{-- <li>
                        <a id="btn-search" href="#"> <i class="icon-search"></i></a>
                    </li> --}}
                    <li>
                        <div class="p-dropdown">
                            <a href="#"><i
                                    class="icon-globe"></i><span>{{ Str::upper(App::currentLocale()) }}</span></a>
                            <ul class="p-dropdown-content">
                                <li><a href="{{ url('locale/es') }}">{{ __('general.spanish') }}</a></li>
                                <li><a href="{{ url('locale/en') }}">{{ __('general.english') }}</a></li>
                            </ul>
                        </div>
                    </li>



                </ul>
            </div>
            <!--end: Header Extras-->
            <!--Navigation Resposnive Trigger-->
            <div id="mainMenu-trigger">
                <a class="lines-button x"><span class="lines"></span></a>
            </div>
            <!--end: Navigation Resposnive Trigger-->
            <!--Navigation-->
            <div id="mainMenu">
                <div class="container">
                    <nav>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('general.home') }}</a></li>
                            <li class="dropdown"><a href="#">Piloto</a>
                                <ul class="dropdown-menu">
                                    <li><a target="_blank"
                                            href="https://wiki.ivao.aero/en/home/training/main/pilot_rank">Rangos</a>
                                    </li>
                                    <li><a target="_blank"
                                            href="http://www.aerocivil.gov.co/servicios-a-la-navegacion/servicio-de-informacion-aeronautica-ais">Cartas
                                            de Navegación</a>
                                    </li>
                                    <li><a target="_blank" href="https://www.ivao.aero/software/altitude">Altitude
                                            Software</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#">ATC</a>

                                <ul class="dropdown-menu">
                                    <li> <a target="_blank"
                                            href="https://wiki.ivao.aero/en/home/training/main/rank_atc">Rangos</a>
                                    </li>
                                    <li> <a href="./?page=fra">¿Qué aeropuerto puedo controlar?</a> </li>
                                    <li> <a href="./?page=gca">Guest Controller Approval</a> </li>
                                    <li> <a target="_blank"
                                            href="http://www.aerocivil.gov.co/servicios-a-la-navegacion/servicio-de-informacion-aeronautica-ais">Cartas
                                            de Navegación</a> </li>
                                    <li> <a target="_blank" href="https://www.ivao.aero/software/aurora">Aurora
                                            Software</a> </li>
                                </ul>


                            </li>
                            <li class="dropdown mega-menu-item"><a href="#">Formacion</a>
                                <ul class="dropdown-menu">
                                    <li class="mega-menu-content">
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <ul>
                                                    <li><a href="shortcode-according.html">Accordions</a></li>
                                                    <li><a href="shortcode-alerts.html">Alerts</a></li>
                                                    <li><a href="shortcode-animations.html">Animations</a></li>
                                                    <li><a href="shortcode-avatar.html">Avatar</a></li>
                                                    <li><a href="shortcode-audio-video.html">Audio & Video</a>
                                                    </li>
                                                    <li><a href="shortcode-blockquotes.html">Blockquotes</a>
                                                    </li>
                                                    <li><a href="component-bootstrap-switch.html">BS
                                                            Switch<span class="badge bg-danger">NEW</span></a>
                                                    </li>
                                                    <li><a href="component-bootstrap-notify.html">BS
                                                            Notify<span class="badge bg-danger">NEW</span></a>
                                                    </li>
                                                    <li><a href="shortcode-breadcrumbs.html">Breadcrumbs</a>
                                                    </li>
                                                    <li><a href="shortcode-buttons.html">Buttons</a></li>
                                                    <li><a href="shortcode-background-image.html">Background
                                                            Image</a></li>
                                                    <li><a href="shortcode-background-overlays.html">Background
                                                            Overlays</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2">
                                                <ul>
                                                    <li><a href="component-charts-chartjs.html">Charts<span
                                                                class="badge bg-danger">NEW</span></a></li>
                                                    <li><a href="component-calendar.html">Calendar<span
                                                                class="badge bg-danger">NEW</span></a></li>
                                                    <li><a href="shortcode-client-logo.html">Clients logos</a>
                                                    </li>
                                                    <li><a href="shortcode-calltoaction.html">Call to
                                                            action</a></li>
                                                    <li><a href="shortcode-carousel.html">Carousel</a></li>
                                                    <li><a href="shortcode-code.html">Code</a></li>
                                                    <li><a href="shortcode-countdown-timer.html">Countdown
                                                            Timers</a></li>
                                                    <li><a href="shortcode-countdown.html">Countdown<span
                                                                class="badge bg-danger">NEW</span></a></li>
                                                    <li><a href="shortcode-counters.html">Counter Numbers</a>
                                                    </li>
                                                    <li><a href="component-clipboard.html">Clipboard<span
                                                                class="badge bg-danger">NEW</span></a></li>
                                                    <li><a href="component-datatable.html">Data Tables<span
                                                                class="badge bg-danger">NEW</span></a></li>
                                                    <li><a href="component-daterangepicker.html">Date & Time
                                                            Pickers</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2">
                                                <ul>
                                                    <li><a href="shortcode-dropcat-highlight.html">Dropcat &
                                                            Highlight</a></li>
                                                    <li><a href="shortcode-dropdowns.html">Dropdowns</a></li>
                                                    <li><a href="shortcode-team-members.html">Team members</a>
                                                    </li>
                                                    <li><a href="shortcode-forms.html">Form Controls</a></li>
                                                    <li><a href="shortcode-form-validation.html">Form
                                                            Validation</a></li>
                                                    <li><a href="shortcode-form-layouts.html">Form Layouts</a>
                                                    </li>
                                                    <li><a href="shortcode-file-upload.html">File upload</a>
                                                    </li>
                                                    <li><a href="shortcode-grid.html">Grid System</a></li>
                                                    <li><a href="shortcode-heading-styles.html">Heading
                                                            Styles</a></li>
                                                    <li><a href="shortcode-icon-boxes.html">Icon Boxes</a></li>
                                                    <li><a href="shortcode-icon-lists.html">Icons</a></li>
                                                    <li><a href="shortcode-images.html">Images</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2">
                                                <ul>
                                                    <li><a href="shortcode-lightbox.html">Lightbox</a></li>
                                                    <li><a href="shortcode-lists.html">Lists</a></li>
                                                    <li><a href="shortcode-labels-badgets.html">Labels &
                                                            Badges</a></li>
                                                    <li><a href="shortcode-maps.html">Maps</a></li>
                                                    <li><a href="shortcode-modal.html">Modal</a></li>
                                                    <li><a href="shortcode-modal-strip.html">Modal Strip</a>
                                                    </li>
                                                    <li><a href="shortcode-navs.html">Navbar & Navs</a></li>
                                                    <li><a href="shortcode-paginations.html">Pagination &
                                                            Pager</a></li>
                                                    <li><a href="shortcode-panels.html">Panels</a></li>
                                                    <li><a href="shortcode-pie-chart.html">Pie charts</a></li>
                                                    <li><a href="shortcode-popover.html">Popover</a></li>
                                                    <li><a href="shortcode-milestone-stats.html">Milestone &
                                                            Stats</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2">
                                                <ul>
                                                    <li><a href="shortcode-pricing-table.html">Pricing
                                                            tables</a></li>
                                                    <li><a href="shortcode-progress-bar.html">Progress bars</a>
                                                    </li>
                                                    <li><a href="shortcode-parallax.html">Parallax</a></li>
                                                    <li><a href="shortcode-particles.html">Particles<span
                                                                class="badge bg-danger">NEW</span></a></li>
                                                    <li><a href="shortcode-responsive-utilities.html">Responsive
                                                            utilities</a></li>
                                                    <li><a href="component-ion-range-slider.html">Range
                                                            Slider<span class="badge bg-danger">NEW</span></a>
                                                    </li>
                                                    <li><a href="shortcode-sections.html">Sections</a></li>
                                                    <li><a href="shortcode-smooth-scrolling.html">Smooth
                                                            Scrolling</a></li>
                                                    <li><a href="shortcode-social-icons.html">Social Icons</a>
                                                    </li>
                                                    <li><a href="shortcode-spinners.html">Spinners<span
                                                                class="badge bg-danger">NEW</span></a></li>
                                                    <li><a href="component-toggles-radio-checkboxes.html">Switch
                                                            Toggle<span class="badge bg-danger">NEW</span></a>
                                                    </li>
                                                    <li><a href="shortcode-shape-dividers.html">Shape
                                                            Dividers<span class="badge bg-danger">NEW</span></a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2">
                                                <ul>
                                                    <li><a href="shortcode-ratings.html">Ratings<span
                                                                class="badge bg-danger">NEW</span></a></li>
                                                    <li><a href="shortcode-tables.html">Tables</a></li>
                                                    <li><a href="shortcode-textbox.html">Text Boxes</a></li>
                                                    <li><a href="shortcode-tabs.html">Tabs</a></li>
                                                    <li><a href="shortcode-testimonial.html">Testimonials</a>
                                                    </li>
                                                    <li><a href="shortcode-toggles.html">Toggles</a></li>
                                                    <li><a href="shortcode-tooltips.html">Tooltips</a></li>
                                                    <li><a href="shortcode-typography.html">Typography</a></li>
                                                    <li><a href="shortcode-text-rotator.html">Text Rotator</a>
                                                    </li>
                                                    <li><a href="shortcode-timeline.html">Timeline<span
                                                                class="badge bg-danger">NEW</span></a></li>
                                                    <li><a href="shortcode-video-background.html">Video
                                                            Background</a></li>
                                                    <li><a href="shortcode-wizard.html">Wizard<span
                                                                class="badge bg-danger">NEW</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#">Foro</a>
                            </li>

                            <li class="d-none  d-md-none d-sm-block"><a
                                    href="{{ url('/') }}">{{ __('general.login') }}</a></li>
                            {{-- <li class="dropdown mega-menu-item"><a href="#">Portfolio</a>
                             <ul class="dropdown-menu">
                                    <li class="mega-menu-content">
                                        <div class="row">
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Grids</li>
                                                    <li><a href="portfolio-2.html">Two Columns</a></li>
                                                    <li><a href="portfolio-3.html">Three Columns</a></li>
                                                    <li><a href="portfolio-4.html">Four Columns</a></li>
                                                    <li><a href="portfolio-5.html">Five Columns</a></li>
                                                    <li><a href="portfolio-6.html">Six Columns</a></li>
                                                    <li><a href="portfolio-sidebar.html">Sidebar version</a>
                                                    </li>
                                                    <li><a href="portfolio-wide-3.html">Wide version</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Masonry</li>
                                                    <li><a href="portfolio-masonry-2.html">Two Columns</a></li>
                                                    <li><a href="portfolio-masonry-3.html">Three Columns<span
                                                                class="badge bg-danger">HOT</span></a></li>
                                                    <li><a href="portfolio-masonry-4.html">Four Columns</a>
                                                    </li>
                                                    <li><a href="portfolio-masonry-5.html">Five Columns</a>
                                                    </li>
                                                    <li><a href="portfolio-masonry-6.html">Six Columns</a></li>
                                                    <li><a href="portfolio-masonry-sidebar.html">Sidebar
                                                            version</a></li>
                                                    <li><a href="portfolio-masonry-wide-3.html">Wide
                                                            version</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Styles</li>
                                                    <li><a href="portfolio-filter-styles.html">Filter
                                                            Styles</a></li>
                                                    <li><a href="portfolio-load-more.html">Load more</a></li>
                                                    <li><a href="portfolio-load-more-sidebar.html">Load more -
                                                            Sidebar</a></li>
                                                    <li><a href="portfolio-infinite-scroll.html">Infinity
                                                            Scroll</a></li>
                                                    <li><a href="portfolio-ajax.html">Portfolio Ajax</a></li>
                                                    <li><a href="portfolio-gallery-modal.html">Gallery
                                                            Modal</a></li>
                                                    <li><a href="portfolio-video-modal.html">Video Modal</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Layouts</li>
                                                    <li><a href="portfolio-sidebar-left.html">Left Sidebar</a>
                                                    </li>
                                                    <li><a href="portfolio-sidebar-right.html">Right
                                                            Sidebar</a></li>
                                                    <li><a href="portfolio-sidebar-both.html">Both Sidebars</a>
                                                    </li>
                                                    <li><a href="portfolio-slider-3.html">Slider Default</a>
                                                    </li>
                                                    <li><a href="portfolio-no-page-title.html">No Page
                                                            Title</a></li>
                                                    <li><a href="portfolio-no-page-title-sidebar.html">No Page
                                                            Title - Sidebar</a></li>
                                                    <li><a href="portfolio-hover-styles.html">Hover Styles</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Single Project</li>
                                                    <li><a href="portfolio-page-grid-gallery.html">Grid
                                                            Gallery</a></li>
                                                    <li><a href="portfolio-page-particles.html">Particles Wide
                                                            Project</a></li>
                                                    <li><a href="portfolio-page-floating-sidebar.html">Floating
                                                            Sidebar</a></li>
                                                    <li><a href="portfolio-page-slider.html">Slider version</a>
                                                    </li>
                                                    <li><a href="portfolio-page-background-image.html">Fullscreen
                                                            image</a></li>
                                                    <li><a href="portfolio-page-background-video.html">Fullscreen
                                                            Video</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-md-12 no-padding">
                                                <div class="d-none d-lg-block m-t-40 m-b-10">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-lg-9 m-t-10">
                                                                <div class="text-center center">
                                                                    <div class="heading-creative">
                                                                        <strong>20+</strong> Amazing Hover
                                                                        Styles
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3"><a
                                                                    href="portfolio-hover-styles.html"
                                                                    class="btn m-l-20 btn-light btn-shadow btn-light-hover btn-light-hover">View
                                                                    All Hover Styles</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="d-block d-lg-none">
                                                    <li><a class="mega-menu-title"
                                                            href="portfolio-hover-styles.html">20+ Amazing
                                                            Hover Styles</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown mega-menu-item"><a href="#">Blog</a>
                                <ul class="dropdown-menu">
                                    <li class="mega-menu-content">
                                        <div class="row">
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Masonry</li>
                                                    <li><a href="blog-masonry-2.html">Two Columns</a></li>
                                                    <li><a href="blog-masonry-3.html">Three Columns<span
                                                                class="badge bg-danger">HOT</span></a></li>
                                                    <li><a href="blog-masonry-4.html">Four Columns</a></li>
                                                    <li><a href="blog-masonry-sidebar.html">Sidebar version</a>
                                                    </li>
                                                    <li><a href="blog-masonry-no-page-title.html">No page
                                                            title</a></li>
                                                    <li><a href="blog-masonry-wide.html">Wide version</a></li>
                                                    <li><a href="blog-masonry-load-more.html">Load More</a>
                                                    </li>
                                                    <li><a href="blog-masonry-infinite-scroll.html">Infinite
                                                            Scroll</a></li>
                                                    <li><a href="blog-masonry-filter.html">Filter
                                                            Categories</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Grids Layout</li>
                                                    <li><a href="blog-1.html">One Column</a></li>
                                                    <li><a href="blog-2.html">Two Columns</a></li>
                                                    <li><a href="blog-3.html">Three Columns</a></li>
                                                    <li><a href="blog-4.html">Four Columns</a></li>
                                                    <li><a href="blog-no-page-title.html">No page title</a>
                                                    </li>
                                                    <li><a href="blog-wide.html">Wide version</a></li>
                                                    <li><a href="blog-load-more.html">Load More</a></li>
                                                    <li><a href="blog-infinite-scroll.html">Infinite Scroll</a>
                                                    </li>
                                                    <li><a href="blog-filter.html">Filter Categories</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Left Image</li>
                                                    <li><a href="blog-left-image-sidebar-right.html">Right
                                                            Sidebar</a></li>
                                                    <li><a href="blog-left-image-sidebar-left.html">Left
                                                            Sidebar</a></li>
                                                    <li><a href="blog-left-image-sidebar-both.html">Both
                                                            Sidebars</a></li>
                                                    <li><a href="blog-left-image-no-sidebar.html">No
                                                            Sidebar</a></li>
                                                    <li><a href="blog-left-image-no-page-title.html">No page
                                                            title</a></li>
                                                    <li><a href="blog-left-image-load-more.html">Load More</a>
                                                    </li>
                                                    <li><a href="blog-left-image-infinite-scroll.html">Infinite
                                                            Scroll</a></li>
                                                    <li><a href="blog-left-image-author-info.html">Author
                                                            Info</a></li>
                                                    <li><a href="blog-left-image-filter.html">Filter
                                                            Categories</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Layouts</li>
                                                    <li><a href="blog-sidebar-left.html">Left Sidebar</a></li>
                                                    <li><a href="blog-sidebar-right.html">Right Sidebar</a>
                                                    </li>
                                                    <li><a href="blog-sidebar-both.html">Both Sidebars</a></li>
                                                    <li><a href="blog-fullwidth.html">Fullwidth</a></li>
                                                    <li class="mega-menu-title">Post Item Styles</li>
                                                    <li><a href="blog-style-shadow.html">Shadow</a></li>
                                                    <li><a href="blog-style-textual.html">Textual</a></li>
                                                    <li><a href="blog-style-grey-bg.html">Grey Background</a>
                                                    </li>
                                                    <li><a href="blog-style-author-info.html">Author Info</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Single Post</li>
                                                    <li><a href="blog-single.html">Default</a></li>
                                                    <li><a href="blog-single-slider.html">Slider</a></li>
                                                    <li><a href="blog-single-video.html">Video</a></li>
                                                    <li><a href="blog-single-audio.html">Audio</a></li>
                                                    <li><a href="blog-single-creative.html">Creative</a></li>
                                                    <li class="mega-menu-title">Comments<span
                                                            class="badge bg-danger">NEW</span></li>
                                                    <li><a href="blog-comments.html#comments">Default
                                                            Comments</a></li>
                                                    <li><a href="blog-comments-disqus.html#comments">Disqus
                                                            Comments</a></li>
                                                    <li><a href="blog-comments-facebook.html#comments">Facebook
                                                            Comments</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown mega-menu-item"><a href="#">Shop</a>
                                <ul class="dropdown-menu">
                                    <li class="mega-menu-content">
                                        <div class="row">
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Columns</li>
                                                    <li><a href="shop-columns-2.html">Two Columns</a></li>
                                                    <li><a href="shop-columns-3.html">Three Columns</a></li>
                                                    <li><a href="shop-columns-4.html">Four Columns</a></li>
                                                    <li><a href="shop-columns-5.html">Five Columns</a></li>
                                                    <li><a href="shop-columns-6.html">Six Columns</a></li>
                                                    <li><a href="shop-sidebar-sticky.html">Sidebar Sticky</a>
                                                    </li>
                                                    <li><a href="shop-wide.html">Wide version</a></li>
                                                    <li><a href="shop-no-page-title.html">No page title</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Layouts</li>
                                                    <li><a href="shop-sidebar-left.html">Left Sidebar</a></li>
                                                    <li><a href="shop-sidebar-right.html">Right Sidebar</a>
                                                    </li>
                                                    <li><a href="shop-sidebar-both.html">Both Sidebars</a></li>
                                                    <li><a href="shop-fullwidth.html">Fullwidth (Wide)</a></li>
                                                    <li class="mega-menu-title">Loading Styles</li>
                                                    <li><a href="shop-load-more.html">Load more</a><a
                                                            href="shop-load-more-sidebar.html">Load Sidebar</a>
                                                    </li>
                                                    <li><a href="shop-infinite-scroll.html">Infinity Scroll</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Order process</li>
                                                    <li><a class="animsition-link" href="shop-cart.html">Shoping
                                                            Cart</a></li>
                                                    <li><a class="animsition-link" href="shop-cart-empty.html">Shoping
                                                            Cart -
                                                            Empty</a></li>
                                                    <li><a class="animsition-link"
                                                            href="shop-checkout.html">Checkout</a></li>
                                                    <li><a class="animsition-link"
                                                            href="shop-checkout-completed.html">Checkout
                                                            Completed</a></li>
                                                    <li><a href="shop-wishlist.html">Wishlist</a></li>
                                                    <li><a href="shop-wishlist-empty.html">Wishlist Empty</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2-5">
                                                <ul>
                                                    <li class="mega-menu-title">Single Product</li>
                                                    <li><a href="shop-single-product.html">Fullwidth</a></li>
                                                    <li><a href="shop-single-product-sidebar-left.html">Left
                                                            Sidebar</a></li>
                                                    <li><a href="shop-single-product-sidebar-right.html">Right
                                                            Sidebar</a></li>
                                                    <li><a href="shop-single-product-sidebar-both.html">Both
                                                            Sidebars</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2-5 p-l-0">
                                                <h4 class="text-theme">BIG SALE<small>Up to</small></h4>
                                                <h2 class="text-lg text-theme lh80 m-b-30">70%</h2>
                                                <p class="m-b-0">The most happiest time of the day!. Morbi
                                                    sagittis, sem quis ipsum dolor sit amet lacinia faucibus.
                                                </p><a class="btn btn-shadow btn-roundeded btn-block m-t-10">SHOP
                                                    NOW!</a><small class="t300">
                                                    <p class="text-center m-0"><em>* Limited time Offer</em>
                                                    </p>
                                                </small>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li> --}}

                        </ul>
                    </nav>
                </div>
            </div>
            <!--end: Navigation-->
        </div>
    </div>
</header>
