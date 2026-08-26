import { FormEvent, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useMetronic } from "../useMetronic";
import { useAuth } from "../auth";
import "../portal.css";

export default function Login() {
  const ready = useMetronic();
  const navigate = useNavigate();
  const { login } = useAuth();
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    document.title = "Log In | 3S Portal";
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
  }, []);

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    const form = new FormData(event.currentTarget);
    setSubmitting(true);
    setError(null);
    try {
      await login(String(form.get("email") ?? ""), String(form.get("password") ?? ""));
      navigate("/dashboard");
    } catch (err) {
      setError(err instanceof Error ? err.message : "These credentials do not match our records.");
    } finally {
      setSubmitting(false);
    }
  };


  if (!ready) {
    return <div className="min-vh-100 d-flex flex-center text-muted">Loading…</div>;
  }

  return (
    <main className="d-flex flex-column flex-root" id="kt_app_root">
      <div className="d-flex flex-column flex-lg-row flex-column-fluid">
        <section className="d-flex flex-lg-row-fluid" aria-label="3S Portal introduction">
          <div className="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
            <img
              className="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
              src="/assets/media/auth/agency.png"
              alt="3S Portal"
            />
            <img
              className="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
              src="/assets/media/auth/agency-dark.png"
              alt="3S Portal"
            />
            <h1 className="text-gray-800 fs-2qx fw-bold text-center mb-7">
              Fast, Efficient and Productive
            </h1>
          </div>
        </section>

        <section className="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
          <div className="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
            <div className="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
              <div className="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
                <form className="form w-100" onSubmit={handleSubmit}>
                  <div className="text-center mb-11">
                    <h2 className="text-dark fw-bolder mb-3">Log In</h2>
                    <div className="text-gray-500 fw-semibold fs-6">
                      Log in here to view dashboard and other features!
                    </div>
                  </div>

                  {error && (
                    <div className="alert alert-danger d-flex align-items-center p-5 mb-8">
                      <span className="fw-semibold">{error}</span>
                    </div>
                  )}


                  <div className="fv-row mb-8">
                    <input
                      type="email"
                      placeholder="Email"
                      name="email"
                      className="form-control bg-transparent"
                      required
                      autoFocus
                      autoComplete="username"
                      aria-label="Email"
                    />
                  </div>
                  <div className="fv-row mb-3">
                    <input
                      type="password"
                      placeholder="Password"
                      name="password"
                      className="form-control bg-transparent"
                      required
                      autoComplete="current-password"
                      aria-label="Password"
                    />
                  </div>

                  <div className="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                    <div />
                    <a href="/forgot-password" className="link-primary">
                      Forgot Password ?
                    </a>
                  </div>

                  <div className="d-grid mb-10">
                    <button type="submit" className="btn btn-primary" disabled={submitting}>
                      <span className={submitting ? "d-none" : "indicator-label"}>Log In</span>
                      {submitting && (
                        <span className="indicator-label">
                          Please wait…
                          <span className="spinner-border spinner-border-sm align-middle ms-2" />
                        </span>
                      )}
                    </button>
                  </div>

                  <a
                    href="/auth/google"
                    className="btn w-100 d-flex align-items-center justify-content-center gap-2 border bg-white mb-3 portal-social-button"
                  >
                    <img src="/assets/media/svg/social-logos/google.svg" alt="" width="20" height="20" />
                    <span className="fw-semibold text-dark">Continue with Google</span>
                  </a>
                  <a
                    href="/auth/facebook"
                    className="btn w-100 d-flex align-items-center justify-content-center gap-2 border bg-white portal-social-button"
                  >
                    <img src="/assets/media/svg/social-logos/facebook.svg" alt="" width="20" height="20" />
                    <span className="fw-semibold text-primary">Continue with Facebook</span>
                  </a>

                  <div className="text-gray-500 text-center fw-semibold fs-6 mt-4">
                    Not a Member yet? <a href="/register" className="link-primary">Register Now!</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </section>
      </div>
    </main>
  );
}