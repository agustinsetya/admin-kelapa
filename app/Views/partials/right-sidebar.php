
<!-- Right Sidebar -->
<div class="right-bar">
    <div data-simplebar class="h-100">
        <div class="rightbar-title d-flex align-items-center p-3">

            <h5 class="m-0 me-2"><?= lang('Files.Theme Customizer') ?></h5>

            <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                <i class="mdi mdi-close noti-icon"></i>
            </a>
        </div>

        <!-- Settings -->
        <hr class="m-0" />

        <div class="p-4">
            <h6 class="mb-3"><?= lang('Files.Layout') ?></h6>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout"
                    id="layout-vertical" value="vertical">
                <label class="form-check-label" for="layout-vertical"><?= lang('Files.Vertical') ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout"
                    id="layout-horizontal" value="horizontal">
                <label class="form-check-label" for="layout-horizontal"><?= lang('Files.Horizontal') ?></label>
            </div>

            <h6 class="mt-4 mb-3 pt-2"><?= lang('Files.Layout Mode') ?></h6>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-mode"
                    id="layout-mode-light" value="light">
                <label class="form-check-label" for="layout-mode-light"><?= lang('Files.Light') ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-mode"
                    id="layout-mode-dark" value="dark">
                <label class="form-check-label" for="layout-mode-dark"><?= lang('Files.Dark') ?></label>
            </div>

            <h6 class="mt-4 mb-3 pt-2"><?= lang('Files.Layout Width') ?></h6>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-width"
                    id="layout-width-fuild" value="fuild" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
                <label class="form-check-label" for="layout-width-fuild"><?= lang('Files.Fluid') ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-width"
                    id="layout-width-boxed" value="boxed" onchange="document.body.setAttribute('data-layout-size', 'boxed')">
                <label class="form-check-label" for="layout-width-boxed"><?= lang('Files.Boxed') ?></label>
            </div>

            <h6 class="mt-4 mb-3 pt-2"><?= lang('Files.Layout Position') ?></h6>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-position"
                    id="layout-position-fixed" value="fixed" onchange="document.body.setAttribute('data-layout-scrollable', 'false')">
                <label class="form-check-label" for="layout-position-fixed"><?= lang('Files.Fixed') ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-position"
                    id="layout-position-scrollable" value="scrollable" onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                <label class="form-check-label" for="layout-position-scrollable"><?= lang('Files.Scrollable') ?></label>
            </div>

            <h6 class="mt-4 mb-3 pt-2"><?= lang('Files.Topbar Color') ?></h6>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="topbar-color"
                    id="topbar-color-light" value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                <label class="form-check-label" for="topbar-color-light"><?= lang('Files.Light') ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="topbar-color"
                    id="topbar-color-dark" value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
                <label class="form-check-label" for="topbar-color-dark"><?= lang('Files.Dark') ?></label>
            </div>

            <h6 class="mt-4 mb-3 pt-2 sidebar-setting"><?= lang('Files.Sidebar Size') ?></h6>

            <div class="form-check sidebar-setting">
                <input class="form-check-input" type="radio" name="sidebar-size"
                    id="sidebar-size-default" value="default" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
                <label class="form-check-label" for="sidebar-size-default"><?= lang('Files.Default') ?></label>
            </div>
            <div class="form-check sidebar-setting">
                <input class="form-check-input" type="radio" name="sidebar-size"
                    id="sidebar-size-compact" value="compact" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
                <label class="form-check-label" for="sidebar-size-compact"><?= lang('Files.Compact') ?></label>
            </div>
            <div class="form-check sidebar-setting">
                <input class="form-check-input" type="radio" name="sidebar-size"
                    id="sidebar-size-small" value="small" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
                <label class="form-check-label" for="sidebar-size-small"><?= lang('Files.Small (Icon View)') ?></label>
            </div>

            <h6 class="mt-4 mb-3 pt-2 sidebar-setting"><?= lang('Files.Sidebar Color') ?></h6>

            <div class="form-check sidebar-setting">
                <input class="form-check-input" type="radio" name="sidebar-color"
                    id="sidebar-color-light" value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
                <label class="form-check-label" for="sidebar-color-light"><?= lang('Files.Light') ?></label>
            </div>
            <div class="form-check sidebar-setting">
                <input class="form-check-input" type="radio" name="sidebar-color"
                    id="sidebar-color-dark" value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
                <label class="form-check-label" for="sidebar-color-dark"><?= lang('Files.Dark') ?></label>
            </div>
            <div class="form-check sidebar-setting">
                <input class="form-check-input" type="radio" name="sidebar-color"
                    id="sidebar-color-brand" value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
                <label class="form-check-label" for="sidebar-color-brand"><?= lang('Files.Brand') ?></label>
            </div>

            <h6 class="mt-4 mb-3 pt-2"><?= lang('Files.Direction') ?></h6>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-direction"
                    id="layout-direction-ltr" value="ltr">
                <label class="form-check-label" for="layout-direction-ltr"><?= lang('Files.LTR') ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-direction"
                    id="layout-direction-rtl" value="rtl">
                <label class="form-check-label" for="layout-direction-rtl"><?= lang('Files.RTL') ?></label>
            </div>

        </div>

    </div> <!-- end slimscroll-menu-->
</div>
<!-- /Right-bar -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>