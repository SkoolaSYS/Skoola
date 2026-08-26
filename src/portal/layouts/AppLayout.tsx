import { ReactNode, useEffect } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { useMetronic } from "../useMetronic";
import { useAuth, Role } from "../auth";
import "../portal.css";

interface AppLayoutProps {
  title?: string;
  role?: Role;
  userName?: string;
  userEmail?: string;
  children: ReactNode;
}

interface MenuItem {
  label: string;
  to: string;
}

/** Mirrors the role-based menu of resources/views/layouts/app.blade.php */
function menuForRole(role: Role): MenuItem[] {
  switch (role) {
    case "parent":
      return [
        { label: "Dashboard", to: "/parent/dashboard" },
        { label: "Attendance", to: "/attendance" },
      ];
    case "teacher":
      return [
        { label: "Dashboard", to: "/dashboard" },
        { label: "Attendance", to: "/class-attendance" },
        { label: "Students", to: "/student" },
      ];
    case "school":
      return [
        { label: "Dashboard", to: "/school/dashboard" },
        { label: "Teacher", to: "/school/teachers" },
        { label: "Student Management", to: "/school/students" },
        { label: "Class Management", to: "/school/class-management" },
        { label: "Touch", to: "/school/reports" },
      ];
    case "admin":
      return [
        { label: "Dashboard", to: "/dashboard" },
        { label: "Admin", to: "/admin" },
      ];
    case "ppd":
      return [{ label: "Dashboard", to: "/ppd/dashboard" }];
    case "state":
      return [{ label: "Dashboard", to: "/state/dashboard" }];
    case "country":
      return [{ label: "Dashboard", to: "/country/dashboard" }];
    default:
      return [{ label: "Dashboard", to: "/dashboard" }];
  }
}

export function AppLayout({
  title = "Dashboard",
  role,
  userName,
  userEmail,
  children,
}: AppLayoutProps) {
  const ready = useMetronic();
  const location = useLocation();
  const navigate = useNavigate();
  const { user, role: authRole, logout } = useAuth();
  const effectiveRole = role ?? authRole;
  const menu = menuForRole(effectiveRole);

  const displayName = userName ?? user?.name ?? "Guest";
  const displayEmail = userEmail ?? user?.email ?? "";

  const handleLogout = async () => {
    await logout();
    navigate("/login");
  };

  useEffect(() => {
    document.title = `${title} | 3S Portal`;
  }, [title]);


  return (
    <div className="d-flex flex-column flex-root app-root" id="kt_app_root">
      <div className="app-page flex-column flex-column-fluid" id="kt_app_page">
        {/*begin::Header*/}
        <div id="kt_app_header" className="app-header" style={{ backgroundColor: "#1e1e2d" }}>
          <div
            className="app-container container-xxl d-flex align-items-stretch justify-content-between"
            id="kt_app_header_container"
          >
            {/*begin::Mobile toggle*/}
            <div
              className="d-flex align-items-center d-lg-none ms-n2 me-2"
              title="Show sidebar menu"
            >
              <div
                className="btn btn-icon btn-active-color-primary w-35px h-35px"
                id="kt_app_header_menu_toggle"
                data-kt-drawer="true"
                data-kt-drawer-name="app-header-menu"
                data-kt-drawer-activate="{default: true, lg: false}"
                data-kt-drawer-overlay="true"
                data-kt-drawer-width="250px"
                data-kt-drawer-direction="end"
                data-kt-drawer-toggle="#kt_app_header_menu_toggle"
              >
                <i className="ki-outline ki-abstract-14 fs-2"></i>
              </div>
            </div>
            {/*end::Mobile toggle*/}

            {/*begin::Logo*/}
            <div className="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
              <Link to="/dashboard" className="d-lg-none">
                <img
                  alt="Logo"
                  src="/assets/media/logos/default-small.svg"
                  className="h-30px"
                />
              </Link>
              <Link to="/dashboard" className="d-none d-lg-flex">
                <img
                  alt="Logo"
                  src="/assets/media/logos/default-dark.svg"
                  className="h-30px"
                />
              </Link>
            </div>
            {/*end::Logo*/}

            {/*begin::Header wrapper*/}
            <div
              className="d-flex align-items-stretch justify-content-between flex-lg-grow-1"
              id="kt_app_header_wrapper"
            >
              {/*begin::Menu wrapper*/}
              <div
                className="app-header-menu app-header-mobile-drawer align-items-stretch"
                data-kt-drawer="true"
                data-kt-drawer-name="app-header-menu"
                data-kt-drawer-activate="{default: true, lg: false}"
                data-kt-drawer-overlay="true"
                data-kt-drawer-width="250px"
                data-kt-drawer-direction="end"
                data-kt-drawer-toggle="#kt_app_header_menu_toggle"
                data-kt-swapper="true"
                data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}"
              >
                <div
                  className="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
                  id="kt_app_header_menu"
                  data-kt-menu="true"
                >
                  {menu.map((item) => (
                    <div
                      key={item.to}
                      className={`menu-item here menu-here-bg menu-lg-down-accordion me-0 me-lg-2 ${
                        location.pathname === item.to ? "here show" : ""
                      }`}
                    >
                      <Link className="menu-link" to={item.to}>
                        <span className="menu-title">{item.label}</span>
                      </Link>
                    </div>
                  ))}
                </div>
              </div>
              {/*end::Menu wrapper*/}

              {/*begin::Navbar*/}
              <div className="app-navbar flex-shrink-0">
                <div className="app-navbar-item ms-1 ms-md-4">
                  <div
                    className="cursor-pointer symbol symbol-35px"
                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                    data-kt-menu-attach="parent"
                    data-kt-menu-placement="bottom-end"
                  >
                    <img src="/assets/media/avatars/blank.png" alt="user" />
                  </div>
                  {/*begin::User account menu*/}
                  <div
                    className="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true"
                  >
                    <div className="menu-item px-3">
                      <div className="menu-content d-flex align-items-center px-3">
                        <div className="symbol symbol-50px me-5">
                          <img alt="Logo" src="/assets/media/avatars/blank.png" />
                        </div>
                        <div className="d-flex flex-column">
                          <div className="fw-bold d-flex align-items-center fs-5">
                            {displayName}
                          </div>
                          <span className="fw-semibold text-muted text-hover-primary fs-7">
                            {displayEmail}
                          </span>
                        </div>
                      </div>
                    </div>
                    <div className="separator my-2"></div>
                    {effectiveRole === "parent" && (
                      <>
                        <div className="menu-item px-5">
                          <Link to="/profile" className="menu-link px-5">
                            Profile
                          </Link>
                        </div>
                        <div className="menu-item px-5">
                          <Link to="/student" className="menu-link px-5">
                            Children
                          </Link>
                        </div>
                      </>
                    )}
                    <div className="menu-item px-5">
                      <button type="button" className="menu-link px-5 btn btn-link text-start w-100" onClick={handleLogout}>
                        Logout
                      </button>
                    </div>

                  </div>
                  {/*end::User account menu*/}
                </div>
              </div>
              {/*end::Navbar*/}
            </div>
            {/*end::Header wrapper*/}
          </div>
        </div>
        {/*end::Header*/}

        {/*begin::Wrapper*/}
        <div className="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
          <div className="app-container container-xxl">
            <div className="app-main flex-column flex-row-fluid" id="kt_app_main">
              {ready ? (
                children
              ) : (
                <div className="py-20 text-center text-muted">Loading…</div>
              )}
              {/*begin::Footer*/}
              <div
                id="kt_app_footer"
                className="app-footer d-flex flex-column flex-md-row align-items-center flex-center flex-md-stack py-2 py-lg-4"
              >
                <div className="text-dark order-2 order-md-1">
                  <span className="text-gray-400 fw-semibold me-1">
                    2023&copy; School System
                  </span>
                </div>
              </div>
              {/*end::Footer*/}
            </div>
          </div>
        </div>
        {/*end::Wrapper*/}
      </div>
    </div>
  );
}
