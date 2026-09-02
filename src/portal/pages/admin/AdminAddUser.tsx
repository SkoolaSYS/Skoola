import { FormEvent, useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api, ApiError } from "@/portal/api";

const ROLE_OPTIONS = ["admin", "country", "state", "ppd", "school", "teacher", "parent"];

export default function AdminAddUser() {
  const navigate = useNavigate();
  const [submitting, setSubmitting] = useState(false);
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [role, setRole] = useState("");

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setSubmitting(true);
    setErrors({});
    const form = new FormData(e.currentTarget);
    try {
      await api("/admin/store", {
        method: "POST",
        form: true,
        body: {
          name: String(form.get("name") ?? ""),
          phone_num: String(form.get("phone_num") ?? ""),
          email: String(form.get("email") ?? ""),
          password: String(form.get("password") ?? ""),
          role,
        },
      });
      navigate("/admin");
    } catch (err) {
      if (err instanceof ApiError && err.errors) setErrors(err.errors);
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <AppLayout title="Create User" role="admin">
      <Card>
        <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
          <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
            <h1 className="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">User List</h1>
          </div>
        </div>

        <div className="card mb-5 mb-xl-10">
          <div className="card-header border-0">
            <div className="card-title m-0">
              <h3 className="fw-bold m-0">Attach User</h3>
            </div>
          </div>

          <div id="kt_account_settings_profile_details" className="collapse show">
            <form id="kt_account_profile_details_form" className="form" onSubmit={handleSubmit}>
              <div className="card-body border-top p-9">
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
                  <div className="col-lg-8 fv-row">
                    <input type="text" name="name" className="form-control form-control-lg form-control-solid" placeholder="Full Name" />
                    {errors.name && <div className="text-danger fs-7 mt-1">{errors.name[0]}</div>}
                  </div>
                </div>

                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label required fw-semibold fs-6">Phone Number</label>
                  <div className="col-lg-8 fv-row">
                    <input type="text" name="phone_num" className="form-control form-control-lg form-control-solid" placeholder="Phone Number" />
                    {errors.phone_num && <div className="text-danger fs-7 mt-1">{errors.phone_num[0]}</div>}
                  </div>
                </div>

                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label required fw-semibold fs-6">Email</label>
                  <div className="col-lg-8 fv-row">
                    <input type="text" name="email" className="form-control form-control-lg form-control-solid" placeholder="Email" />
                    {errors.email && <div className="text-danger fs-7 mt-1">{errors.email[0]}</div>}
                  </div>
                </div>

                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label required fw-semibold fs-6">Password</label>
                  <div className="col-lg-8 fv-row">
                    <input type="password" name="password" className="form-control form-control-lg form-control-solid" placeholder="Password" />
                    {errors.password && <div className="text-danger fs-7 mt-1">{errors.password[0]}</div>}
                  </div>
                </div>

                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label required fw-semibold fs-6">Role</label>
                  <div className="col-lg-8 fv-row">
                    <select
                      className="form-select form-select-lg form-select-solid"
                      value={role}
                      onChange={(e) => setRole(e.target.value)}
                    >
                      <option value="">-- Select Role --</option>
                      {ROLE_OPTIONS.map((r) => (
                        <option key={r} value={r}>{r}</option>
                      ))}
                    </select>
                    {errors.role && <div className="text-danger fs-7 mt-1">{errors.role[0]}</div>}
                  </div>
                </div>
              </div>

              <div className="card-footer d-flex justify-content-end py-6 px-9">
                <Link to="/admin" className="btn btn-light btn-active-light-primary me-2">Cancel</Link>
                <button type="submit" className="btn btn-primary" disabled={submitting}>
                  {submitting ? "Creating…" : "Create User"}
                </button>
              </div>
            </form>
          </div>
        </div>
      </Card>
    </AppLayout>
  );
}
