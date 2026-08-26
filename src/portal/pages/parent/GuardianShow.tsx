import { useEffect, useState } from "react";
import { useNavigate, useParams, Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface Guardian {
  id: number;
  name: string;
  ic?: string | null;
  phone_num?: string | null;
  email?: string | null;
  username?: string | null;
  occupation?: string | null;
  relationship?: string | null;
  address?: string | null;
}

interface GuardianShowResponse {
  guardian: Guardian;
  state?: string;
  city?: string;
  postcode?: string;
}

export default function GuardianShow() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [data, setData] = useState<GuardianShowResponse | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!id) return;
    api<GuardianShowResponse>(`/profile/guardian/${id}`)
      .then(setData)
      .catch(() => setData(null))
      .finally(() => setLoading(false));
  }, [id]);

  const handleDelete = async () => {
    if (!id || !confirm("Are you sure you want to delete this guardian?")) return;
    try {
      await api(`/profile/guardian/${id}/delete`, { method: "DELETE", form: true });
      navigate("/profile");
    } catch {
      /* ignore */
    }
  };

  if (loading) {
    return (
      <AppLayout title="Additional Guardian Profile" role="parent">
        <div className="card"><div className="card-body text-center text-muted py-10">Loading…</div></div>
      </AppLayout>
    );
  }

  const guardian = data?.guardian;

  return (
    <AppLayout title="Additional Guardian Profile" role="parent">
      <div className="card">
        <div className="card-body">
          <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
            <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
              <h1 className="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">Account</h1>
            </div>
          </div>

          {!guardian ? (
            <div className="alert alert-warning">Guardian not found.</div>
          ) : (
            <>
              <div className="card mb-5 mb-xl-10">
                <div className="card-body pt-9 pb-0">
                  <div className="d-flex flex-wrap flex-sm-nowrap">
                    <div className="flex-grow-1">
                      <div className="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div className="d-flex flex-column">
                          <div className="d-flex align-items-center mb-2">
                            <span className="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{guardian.name}</span>
                          </div>
                          <div className="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                            <a className="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                              <i className="ki-duotone ki-profile-circle fs-4 me-1"><span className="path1"></span><span className="path2"></span><span className="path3"></span></i>
                              Additional guardian
                            </a>
                            <a className="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                              <i className="ki-duotone ki-sms fs-4 me-1"><span className="path1"></span><span className="path2"></span></i>
                              {guardian.email}
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div className="card mb-5 mb-xl-10" id="kt_profile_details_view">
                <div className="card-header cursor-pointer">
                  <div className="card-title m-0">
                    <h3 className="fw-bold m-0">Add guardian profile details</h3>
                  </div>
                  <div className="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="button" className="btn btn-danger align-self-center me-2" onClick={handleDelete}>
                      Delete Profile
                    </button>
                    <Link to={`/profile/guardian/${guardian.id}/edit`} className="btn btn-primary">
                      Edit profile
                    </Link>
                  </div>
                </div>
                <div className="card-body p-9">
                  <div className="row mb-7">
                    <label className="col-lg-4 fw-semibold text-muted">Full name</label>
                    <div className="col-lg-8"><span className="fw-semibold fs-6 text-gray-800">{guardian.name}</span></div>
                  </div>
                  <div className="row mb-7">
                    <label className="col-lg-4 fw-semibold text-muted">IC</label>
                    <div className="col-lg-8 fv-row"><span className="fw-semibold text-gray-800 fs-6">{guardian.ic}</span></div>
                  </div>
                  <div className="row mb-7">
                    <label className="col-lg-4 fw-semibold text-muted">Phone number</label>
                    <div className="col-lg-8 d-flex align-items-center"><span className="fw-semibold fs-6 text-gray-800">{guardian.phone_num}</span></div>
                  </div>
                  <div className="row mb-7">
                    <label className="col-lg-4 fw-semibold text-muted">Email</label>
                    <div className="col-lg-8">
                      <span className="fw-semibold fs-6 text-gray-800 text-hover-primary">{guardian.email}</span>{" "}
                      <span className="badge badge-success">Verified</span>
                    </div>
                  </div>
                  <div className="row mb-7">
                    <label className="col-lg-4 fw-semibold text-muted">Username</label>
                    <div className="col-lg-8"><span className="fw-semibold fs-6 text-gray-800">{guardian.username}</span></div>
                  </div>
                  <div className="row mb-7">
                    <label className="col-lg-4 fw-semibold text-muted">Occupation</label>
                    <div className="col-lg-8"><span className="fw-semibold fs-6 text-gray-800">{guardian.occupation}</span></div>
                  </div>
                  <div className="row mb-7">
                    <label className="col-lg-4 fw-semibold text-muted">Relationship</label>
                    <div className="col-lg-8"><span className="fw-semibold fs-6 text-gray-800">{guardian.relationship ?? "N/A"}</span></div>
                  </div>
                  <div className="row mb-7">
                    <label className="col-lg-4 fw-semibold text-muted">Address</label>
                    <div className="col-lg-8"><span className="fw-semibold fs-6 text-gray-800">{guardian.address}</span></div>
                  </div>
                  <div className="row mb-7">
                    <label className="col-lg-4 fw-semibold text-muted">Postcode</label>
                    <div className="col-lg-8"><span className="fw-semibold fs-6 text-gray-800">{data?.postcode}</span></div>
                  </div>
                  <div className="row mb-10">
                    <label className="col-lg-4 fw-semibold text-muted">City</label>
                    <div className="col-lg-8"><span className="fw-semibold fs-6 text-gray-800">{data?.city}</span></div>
                  </div>
                  <div className="row mb-10">
                    <label className="col-lg-4 fw-semibold text-muted">State</label>
                    <div className="col-lg-8"><span className="fw-semibold fs-6 text-gray-800">{data?.state}</span></div>
                  </div>
                </div>
              </div>
            </>
          )}
        </div>
      </div>
    </AppLayout>
  );
}
