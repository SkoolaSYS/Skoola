import { FormEvent, useEffect, useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface UserData {
  id: number;
  name: string;
  username?: string | null;
  ic?: string | null;
  phone_num?: string | null;
  email: string;
  occupation?: string | null;
  relationship?: string | null;
  address?: string | null;
}

export default function EditProfile() {
  const navigate = useNavigate();
  const [user, setUser] = useState<UserData | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    api<{ user: UserData }>("/profile/edit")
      .then((res) => setUser(res.user))
      .catch(() => setUser(null))
      .finally(() => setLoading(false));
  }, []);

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setSubmitting(true);
    setError(null);
    const form = new FormData(e.currentTarget);
    const body = Object.fromEntries(form.entries());
    try {
      await api("/profile/update", { method: "POST", body, form: true });
      navigate("/profile");
    } catch {
      setError("Unable to update profile.");
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <AppLayout title="Edit Profile" role="parent">
      <div className="card">
        <div className="card-body">
          <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
            <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
              <h1 className="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">
                Account
              </h1>
            </div>
          </div>

          <div className="card mb-5 mb-xl-10">
            <div className="card-header border-0">
              <div className="card-title m-0">
                <h3 className="fw-bold m-0">Edit profile</h3>
              </div>
            </div>
            <div className="collapse show">
              {loading ? (
                <div className="text-center text-muted py-10">Loading…</div>
              ) : !user ? (
                <div className="alert alert-warning m-9">Unable to load profile.</div>
              ) : (
                <form className="form" onSubmit={handleSubmit}>
                  <div className="card-body border-top p-9">
                    {error && <div className="alert alert-danger">{error}</div>}
                    <input type="hidden" name="id" defaultValue={user.id} />

                    <div className="row mb-6">
                      <label className="col-lg-4 col-form-label required fw-semibold fs-6">Full name</label>
                      <div className="col-lg-8 fv-row">
                        <input type="text" name="name" className="form-control form-control-lg form-control-solid" defaultValue={user.name} required />
                      </div>
                    </div>

                    <div className="row mb-6">
                      <label className="col-lg-4 col-form-label fw-semibold fs-6">Username</label>
                      <div className="col-lg-8 fv-row">
                        <input type="text" name="username" className="form-control form-control-lg form-control-solid" defaultValue={user.username ?? ""} />
                      </div>
                    </div>

                    <div className="row mb-6">
                      <label className="col-lg-4 col-form-label required fw-semibold fs-6">IC</label>
                      <div className="col-lg-8 fv-row">
                        <input type="text" name="ic" className="form-control form-control-lg form-control-solid" defaultValue={user.ic ?? ""} required />
                      </div>
                    </div>

                    <div className="row mb-6">
                      <label className="col-lg-4 col-form-label fw-semibold fs-6">Phone number</label>
                      <div className="col-lg-8 fv-row">
                        <input type="tel" name="phone_num" className="form-control form-control-lg form-control-solid" defaultValue={user.phone_num ?? ""} required />
                      </div>
                    </div>

                    <div className="row mb-6">
                      <label className="col-lg-4 col-form-label fw-semibold fs-6">Email</label>
                      <div className="col-lg-8 fv-row">
                        <input type="email" name="email" className="form-control form-control-lg form-control-solid" defaultValue={user.email} required />
                      </div>
                    </div>

                    <div className="row mb-6">
                      <label className="col-lg-4 col-form-label fw-semibold fs-6">Occupation</label>
                      <div className="col-lg-8 fv-row">
                        <input type="text" name="occupation" className="form-control form-control-lg form-control-solid" defaultValue={user.occupation ?? ""} />
                      </div>
                    </div>

                    <div className="row mb-6">
                      <label className="col-lg-4 col-form-label fw-semibold fs-6">Relationship</label>
                      <div className="col-lg-8 fv-row">
                        <select name="relationship" className="form-control form-control-lg form-control-solid" defaultValue={user.relationship ?? ""}>
                          <option value="">-- Select relationship --</option>
                          <option value="Father">Father</option>
                          <option value="Mother">Mother</option>
                          <option value="Guardian">Guardian</option>
                        </select>
                      </div>
                    </div>

                    <div className="row mb-6">
                      <label className="col-lg-4 col-form-label fw-semibold fs-6">Address</label>
                      <div className="col-lg-8 fv-row">
                        <input type="text" name="address" className="form-control form-control-lg form-control-solid" defaultValue={user.address ?? ""} required />
                      </div>
                    </div>
                  </div>

                  <div className="card-footer d-flex justify-content-end py-6 px-9">
                    <Link to="/profile" className="btn btn-light btn-active-light-primary me-2">
                      Cancel
                    </Link>
                    <button type="submit" className="btn btn-primary" disabled={submitting}>
                      {submitting ? "Saving…" : "Save"}
                    </button>
                  </div>
                </form>
              )}
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
