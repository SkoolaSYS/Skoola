import { FormEvent, useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

export default function GuardianAdd() {
  const navigate = useNavigate();
  const [error, setError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setSubmitting(true);
    setError(null);
    const form = new FormData(e.currentTarget);
    const body = Object.fromEntries(form.entries());
    try {
      await api("/profile/guardian/store", { method: "POST", body, form: true });
      navigate("/profile");
    } catch {
      setError("Unable to add guardian. Please check the form.");
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <AppLayout title="Add Secondary Parent" role="parent">
      <div className="card">
        <div className="card-body">
          <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
            <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
              <h1 className="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">Account</h1>
            </div>
          </div>

          <div className="card mb-5 mb-xl-10">
            <div className="card-header border-0">
              <div className="card-title m-0">
                <h3 className="fw-bold m-0">Add guardian profile details</h3>
              </div>
            </div>
            <div className="collapse show">
              <form className="form" onSubmit={handleSubmit}>
                <div className="card-body border-top p-9">
                  {error && <div className="alert alert-danger">{error}</div>}

                  <div className="row mb-6">
                    <label className="col-lg-4 col-form-label required fw-semibold fs-6">Full name</label>
                    <div className="col-lg-8 fv-row">
                      <input type="text" name="name" className="form-control form-control-lg form-control-solid" placeholder="Full name" required />
                    </div>
                  </div>

                  <div className="row mb-6">
                    <label className="col-lg-4 col-form-label required">Username</label>
                    <div className="col-lg-8 fv-row">
                      <input type="text" name="username" className="form-control form-control-lg form-control-solid" placeholder="Username" required />
                    </div>
                  </div>

                  <div className="row mb-6">
                    <label className="col-lg-4 col-form-label required fw-semibold fs-6">IC</label>
                    <div className="col-lg-8 fv-row">
                      <input type="text" name="ic" className="form-control form-control-lg form-control-solid" placeholder="IC" required />
                    </div>
                  </div>

                  <div className="row mb-6">
                    <label className="col-lg-4 col-form-label fw-semibold fs-6">Phone number</label>
                    <div className="col-lg-8 fv-row">
                      <input type="tel" name="phone_num" className="form-control form-control-lg form-control-solid" placeholder="Phone number" required />
                    </div>
                  </div>

                  <div className="row mb-6">
                    <label className="col-lg-4 col-form-label fw-semibold fs-6">Email</label>
                    <div className="col-lg-8 fv-row">
                      <input type="email" name="email" className="form-control form-control-lg form-control-solid" placeholder="Email" required />
                    </div>
                  </div>

                  <div className="row mb-6">
                    <label className="col-lg-4 col-form-label required">Occupation</label>
                    <div className="col-lg-8 fv-row">
                      <input type="text" name="occupation" className="form-control form-control-lg form-control-solid" placeholder="Occupation" required />
                    </div>
                  </div>

                  <div className="row mb-6">
                    <label className="col-lg-4 col-form-label required">Relationship</label>
                    <div className="col-lg-8 fv-row">
                      <select name="relationship" className="form-control form-control-lg form-control-solid" required defaultValue="">
                        <option value="">Select relationship</option>
                        <option value="Father">Father</option>
                        <option value="Mother">Mother</option>
                        <option value="Guardian">Guardian</option>
                      </select>
                    </div>
                  </div>

                  <div className="row mb-6">
                    <label className="col-lg-4 col-form-label fw-semibold fs-6">Address</label>
                    <div className="col-lg-8 fv-row">
                      <input type="text" name="address" className="form-control form-control-lg form-control-solid" placeholder="Address" required />
                    </div>
                  </div>

                  <div className="row mb-6">
                    <label className="col-lg-4 col-form-label required">Password</label>
                    <div className="col-lg-8 fv-row">
                      <input type="password" name="password" className="form-control form-control-lg form-control-solid" placeholder="Password" required autoComplete="new-password" />
                      <div className="text-muted mt-2">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
                    </div>
                  </div>

                  <div className="row mb-6">
                    <label className="col-lg-4 col-form-label required">Confirm password</label>
                    <div className="col-lg-8 fv-row">
                      <input type="password" name="password_confirmation" className="form-control form-control-lg form-control-solid" placeholder="Confirm password" required autoComplete="new-password" />
                    </div>
                  </div>
                </div>

                <div className="card-footer d-flex justify-content-end py-6 px-9">
                  <Link to="/profile" className="btn btn-light btn-active-light-primary me-2">Cancel</Link>
                  <button type="submit" className="btn btn-primary" disabled={submitting}>
                    {submitting ? "Saving…" : "Save"}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
