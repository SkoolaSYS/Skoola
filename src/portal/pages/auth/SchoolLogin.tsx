import { FormEvent, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useMetronic } from "../../useMetronic";
import { api, ApiError } from "../../api";
import "../../portal.css";

/** Mirrors school/login.blade.php */
export default function SchoolLogin() {
  const ready = useMetronic();
  const navigate = useNavigate();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    document.title = "School Log In | 3S Portal";
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

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setSubmitting(true);
    setError(null);
    try {
      await api("/login", { method: "POST", body: { email, password }, form: true });
      navigate("/school/login");
    } catch (err) {
      if (err instanceof ApiError) setError(err.errors ? Object.values(err.errors)[0]?.[0] ?? err.message : err.message);
      else setError("Unable to log in.");
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
        <section className="d-flex flex-lg-row-fluid" aria-label="School login introduction">
          <div className="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
            <img
              className="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
              src="/assets/media/auth/membership2.png"
              alt=""
            />
            <img
              className="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
              src="/assets/media/auth/membership-dark.png"
              alt=""
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
                    {error && <div className="alert alert-danger text-center mt-3">{error}</div>}
                    <h1 className="text-dark fw-bolder mb-3">School Log In</h1>
                    <div className="text-gray-500 fw-semibold fs-6">
                      Log in here to view dashboard and other features!
                    </div>
                  </div>

                  <div className="fv-row mb-8">
                    <input
                      type="text"
                      placeholder="Email"
                      name="email"
                      className="form-control bg-transparent"
                      required
                      autoFocus
                      autoComplete="username"
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
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
                      value={password}
                      onChange={(e) => setPassword(e.target.value)}
                    />
                  </div>

                  <div className="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                    <div />
                    <a href="/forgot-password" className="link-primary">
                      Forgot Password ?
                    </a>
                  </div>

                  <div className="d-grid mb-10">
                    <button type="submit" id="kt_sign_in_submit" className="btn btn-primary" disabled={submitting}>
                      <span className={submitting ? "d-none" : "indicator-label"}>Log In</span>
                      {submitting && (
                        <span className="indicator-label">
                          Please wait...
                          <span className="spinner-border spinner-border-sm align-middle ms-2" />
                        </span>
                      )}
                    </button>
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
