import { useEffect } from "react";
import { useMetronic } from "@/portal/useMetronic";

export default function Welcome() {
  const ready = useMetronic();

  useEffect(() => {
    document.title = "Metronic - The World's #1 Selling Bootstrap Admin Template by Keenthemes";
  }, []);

  if (!ready) {
    return <div className="min-vh-100 d-flex flex-center text-muted">Loading…</div>;
  }

  return (
    <div className="d-flex flex-column flex-root" id="kt_app_root">
      <div className="mb-0" id="home">
        <div
          className="bgi-no-repeat bgi-size-contain bgi-position-x-center bgi-position-y-bottom landing-dark-bg"
          style={{ backgroundImage: "url(/assets/media/svg/illustrations/landing.svg)" }}
        >
          <div className="landing-header">
            <div className="container">
              <div className="mb-0">
                <div className="d-flex align-items-center justify-content-between">
                  <div className="d-flex align-items-center flex-equal">
                    <button className="btn btn-icon btn-active-color-primary me-3 d-flex d-lg-none" id="kt_landing_menu_toggle">
                      <i className="ki-duotone ki-abstract-14 fs-2hx">
                        <span className="path1" />
                        <span className="path2" />
                      </i>
                    </button>
                    <a href="/welcome">
                      <img alt="Logo" src="/assets/media/logos/landing-dark.svg" className="logo-sticky h-20px h-lg-30px" />
                    </a>
                  </div>
                  <div className="d-lg-block" id="kt_header_nav_wrapper">
                    <div
                      className="d-lg-block p-5 p-lg-0"
                      data-kt-drawer="true"
                      data-kt-drawer-name="landing-menu"
                    >
                      <div
                        className="menu menu-column flex-nowrap menu-rounded menu-lg-row menu-title-gray-500 menu-state-title-primary nav nav-flush fs-5 fw-semibold"
                        id="kt_landing_menu"
                      />
                    </div>
                  </div>
                  <div className="flex-equal text-end ms-1">
                    <br />
                    <a href="/login" className="btn btn-success">Sign In</a>{" "}
                    <a href="/register" className="btn btn-success">Sign Up</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div className="d-flex flex-column flex-center w-100 min-h-350px min-h-lg-500px px-9">
            <div className="text-center mb-5 mb-lg-10 py-10 py-lg-20">
              <h1 className="text-white lh-base fw-bold fs-2x fs-lg-3x mb-15">
                Build An Outstanding Solutions
                <br />
                with{" "}
                <span
                  style={{
                    background: "linear-gradient(to right, #12CE5D 0%, #FFD80C 100%)",
                    WebkitBackgroundClip: "text",
                    WebkitTextFillColor: "transparent",
                  }}
                >
                  <span id="kt_landing_hero_text">The Best Theme Ever</span>
                </span>
              </h1>
            </div>
          </div>
        </div>

        <div className="landing-dark-separator" />

        <div className="container">
          <div className="d-flex flex-column flex-md-row flex-stack py-7 py-lg-10">
            <div className="d-flex align-items-center order-2 order-md-1">
              <span className="mx-5 fs-6 fw-semibold text-gray-600 pt-1">&copy; 2023 Keenthemes Inc.</span>
            </div>
            <ul className="menu menu-gray-600 menu-hover-primary fw-semibold fs-6 fs-md-5 order-1 mb-5 mb-md-0">
              <li className="menu-item">
                <a href="https://keenthemes.com" target="_blank" rel="noreferrer" className="menu-link px-2">About</a>
              </li>
              <li className="menu-item mx-5">
                <a href="https://devs.keenthemes.com" target="_blank" rel="noreferrer" className="menu-link px-2">Support</a>
              </li>
              <li className="menu-item">
                <a href="" target="_blank" rel="noreferrer" className="menu-link px-2">Purchase</a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div id="kt_scrolltop" className="scrolltop" data-kt-scrolltop="true">
        <i className="ki-duotone ki-arrow-up">
          <span className="path1" />
          <span className="path2" />
        </i>
      </div>
    </div>
  );
}
