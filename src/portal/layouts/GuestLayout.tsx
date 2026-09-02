import { ReactNode, useEffect } from "react";
import { useMetronic } from "../useMetronic";
import "../portal.css";

interface GuestLayoutProps {
  title?: string;
  children: ReactNode;
}

/**
 * Reproduces the Metronic centered "authentication card" shell used by the
 * simple auth pages (forgot-password, reset-password, verify-email,
 * confirm-password, two-factor-challenge). The original Blade views for
 * these pages extend `layouts.guest` (a minimal Jetstream/Tailwind shell),
 * but this project only ships Metronic Bootstrap assets, so the same card
 * layout used by Login/Register is reproduced here for visual consistency.
 */
export function GuestLayout({ title, children }: GuestLayoutProps) {
  const ready = useMetronic();

  useEffect(() => {
    if (title) document.title = `${title} | 3S Portal`;
    document.body.classList.add(
      "app-blank",
      "bgi-size-cover",
      "bgi-attachment-fixed",
      "bgi-position-center",
      "portal-login-page",
    );
    return () => {
      document.body.classList.remove(
        "app-blank",
        "bgi-size-cover",
        "bgi-attachment-fixed",
        "bgi-position-center",
        "portal-login-page",
      );
    };
  }, [title]);

  if (!ready) {
    return <div className="min-vh-100 d-flex flex-center text-muted">Loading…</div>;
  }

  return (
    <main className="d-flex flex-column flex-root" id="kt_app_root">
      <div className="d-flex flex-column flex-column-fluid flex-lg-row flex-column-fluid">
        <div className="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
          <div className="d-flex flex-center flex-column flex-lg-row-fluid">
            <div className="w-lg-500px p-10">{children}</div>
          </div>
        </div>
        <div className="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" />
      </div>
    </main>
  );
}
