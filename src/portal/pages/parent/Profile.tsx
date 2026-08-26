import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface ParentInfo {
  id: number;
  name: string;
  email: string;
  ic?: string | null;
  phone_num?: string | null;
  occupation?: string | null;
  relationship?: string | null;
  username?: string | null;
  address?: string | null;
}

interface Guardian {
  id: number;
  name: string;
  phone_num?: string | null;
  email?: string | null;
}

interface ProfileResponse {
  parent: ParentInfo;
  postcode?: { name?: string } | null;
  citie?: { name?: string } | null;
  state?: { name?: string } | null;
  guardians: Guardian[];
}

export default function Profile() {
  const [data, setData] = useState<ProfileResponse | null>(null);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState<{ type: "success" | "error"; text: string } | null>(null);

  useEffect(() => {
    api<ProfileResponse>("/profile")
      .then(setData)
      .catch(() => setData(null))
      .finally(() => setLoading(false));
  }, []);

  const handleDelete = async (id: number) => {
    if (!confirm("Are you sure you want to delete this guardian?")) return;
    try {
      await api(`/profile/guardian/${id}/delete`, { method: "DELETE", form: true });
      setMessage({ type: "success", text: "Guardian deleted successfully." });
      setData((prev) =>
        prev ? { ...prev, guardians: prev.guardians.filter((g) => g.id !== id) } : prev
      );
    } catch {
      setMessage({ type: "error", text: "Unable to delete guardian." });
    }
  };

  if (loading) {
    return (
      <AppLayout title="Profile" role="parent">
        <div className="card">
          <div className="card-body text-center text-muted py-10">Loading…</div>
        </div>
      </AppLayout>
    );
  }

  const parent = data?.parent;
  const guardians = data?.guardians ?? [];

  return (
    <AppLayout title="Profile" role="parent">
      <div className="card mb-5 mb-xl-10">
        <div className="card-body">
          <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
            <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
              <h1 className="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">
                Account
              </h1>
            </div>
          </div>

          {!parent ? (
            <div className="alert alert-warning">Unable to load your profile.</div>
          ) : (
            <>
              <div className="card mb-5 mb-xl-10">
                <div className="card-body pt-9 pb-0">
                  <div className="d-flex flex-wrap flex-sm-nowrap">
                    <div className="flex-grow-1">
                      <div className="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div className="d-flex flex-column">
                          <div className="d-flex align-items-center mb-2">
                            <span className="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                              {parent.name}
                            </span>
                          </div>
                          <div className="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                            <a className="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                              <i className="ki-duotone ki-profile-circle fs-4 me-1">
                                <span className="path1"></span>
                                <span className="path2"></span>
                                <span className="path3"></span>
                              </i>
                              Primary
                            </a>
                            <a className="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                              <i className="ki-duotone ki-sms fs-4 me-1">
                                <span className="path1"></span>
                                <span className="path2"></span>
                              </i>
                              {parent.email}
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              {parent.ic == null && parent.address == null && (
                <div className="alert alert-warning">Please complete your profile details.</div>
              )}

              {message && (
                <div className={`alert alert-${message.type === "success" ? "success" : "danger"}`}>
                  {message.text}
                </div>
              )}

              <div className="card mb-5 mb-xl-10" id="kt_profile_details_view">
                <div className="card-header cursor-pointer">
                  <div className="card-title m-0">
                    <h3 className="fw-bold m-0">Profile details</h3>
                  </div>
                  <Link to="/profile/edit" className="btn btn-sm btn-primary align-self-center">
                    Edit profile
                  </Link>
                </div>
                <div className="card-body p-9">
                  <div className="row">
                    <div className="col-md-6">
                      <div className="row mb-7">
                        <label className="col-lg-4 fw-semibold text-muted">Full name</label>
                        <div className="col-lg-8">
                          <span className="fw-semibold fs-6 text-gray-800">{parent.name}</span>
                        </div>
                      </div>
                      <div className="row mb-7">
                        <label className="col-lg-4 fw-semibold text-muted">IC</label>
                        <div className="col-lg-8">
                          <span className="fw-semibold fs-6 text-gray-800">{parent.ic ?? "N/A"}</span>
                        </div>
                      </div>
                      <div className="row mb-7">
                        <label className="col-lg-4 fw-semibold text-muted">Phone number</label>
                        <div className="col-lg-8 d-flex align-items-center">
                          <span className="fw-semibold fs-6 text-gray-800">{parent.phone_num}</span>
                        </div>
                      </div>
                      <div className="row mb-7">
                        <label className="col-lg-4 fw-semibold text-muted">Email</label>
                        <div className="col-lg-8">
                          <span className="fw-semibold fs-6 text-gray-800 text-hover-primary">
                            {parent.email}
                          </span>{" "}
                          <span className="badge badge-success">Verified</span>
                        </div>
                      </div>
                      <div className="row mb-7">
                        <label className="col-lg-4 fw-semibold text-muted">Occupation</label>
                        <div className="col-lg-8">
                          <span className="fw-semibold fs-6 text-gray-800">
                            {parent.occupation ?? "N/A"}
                          </span>
                        </div>
                      </div>
                      <div className="row mb-7">
                        <label className="col-lg-4 fw-semibold text-muted">Relationship</label>
                        <div className="col-lg-8">
                          <span className="fw-semibold fs-6 text-gray-800">
                            {parent.relationship ?? "N/A"}
                          </span>
                        </div>
                      </div>
                      <div className="row mb-7">
                        <label className="col-lg-4 fw-semibold text-muted">Username</label>
                        <div className="col-lg-8">
                          <span className="fw-semibold fs-6 text-gray-800">
                            {parent.username ?? "N/A"}
                          </span>
                        </div>
                      </div>
                    </div>

                    <div className="col-md-6">
                      <div className="row mb-7">
                        <label className="col-lg-4 fw-semibold text-muted">Address</label>
                        <div className="col-lg-8">
                          <span className="fw-semibold fs-6 text-gray-800">
                            {parent.address ?? "N/A"}
                          </span>
                        </div>
                      </div>
                      <div className="row mb-7">
                        <label className="col-lg-4 fw-semibold text-muted">Postcode</label>
                        <div className="col-lg-8">
                          <span className="fw-semibold fs-6 text-gray-800">
                            {data?.postcode?.name ?? "N/A"}
                          </span>
                        </div>
                      </div>
                      <div className="row mb-7">
                        <label className="col-lg-4 fw-semibold text-muted">City</label>
                        <div className="col-lg-8">
                          <span className="fw-semibold fs-6 text-gray-800">
                            {data?.citie?.name ?? "N/A"}
                          </span>
                        </div>
                      </div>
                      <div className="row mb-7">
                        <label className="col-lg-4 fw-semibold text-muted">State</label>
                        <div className="col-lg-8">
                          <span className="fw-semibold fs-6 text-gray-800">
                            {data?.state?.name ?? "N/A"}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div className="card">
                  <div className="card-header cursor-pointer">
                    <div className="card-title m-0">
                      <h3 className="fw-bold m-0">List of additional guardians</h3>
                    </div>
                    <Link
                      to="/profile/guardian/create"
                      className="btn btn-sm btn-primary align-self-center"
                    >
                      Add guardian
                    </Link>
                  </div>
                  <div id="kt_referred_users_tab_content" className="tab-content">
                    <div id="kt_referrals_1" className="card-body p-0 tab-pane fade show active" role="tabpanel">
                      <div className="table-responsive">
                        <table className="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                          <thead className="border-gray-200 fs-5 fw-semibold bg-lighten">
                            <tr>
                              <th className="min-w-175px ps-9">No.</th>
                              <th className="min-w-150px px-0">Name</th>
                              <th className="min-w-150px px-0">Phone number</th>
                              <th className="min-w-150px px-0">Email</th>
                              <th className="min-w-125px text-center" colSpan={3}>
                                Action
                              </th>
                            </tr>
                          </thead>
                          <tbody className="fs-6 fw-semibold text-gray-600">
                            {guardians.length === 0 ? (
                              <tr>
                                <td className="text-center" colSpan={5}>
                                  No additional guardian added.
                                </td>
                              </tr>
                            ) : (
                              guardians.map((guardian, index) => (
                                <tr key={guardian.id}>
                                  <td className="ps-9">{index + 1}</td>
                                  <td className="ps-0">{guardian.name}</td>
                                  <td className="ps-0">{guardian.phone_num ?? "-"}</td>
                                  <td className="ps-0">{guardian.email ?? "-"}</td>
                                  <td className="text-center d-flex justify-content-center">
                                    <div className="d-flex gap-2">
                                      <Link
                                        to={`/profile/guardian/${guardian.id}`}
                                        className="btn btn-sm btn-light align-self-center"
                                      >
                                        View
                                      </Link>
                                      <Link
                                        to={`/profile/guardian/${guardian.id}/edit`}
                                        className="btn btn-sm btn-primary me-1"
                                      >
                                        Edit
                                      </Link>
                                      <button
                                        type="button"
                                        className="btn btn-sm btn-danger"
                                        onClick={() => handleDelete(guardian.id)}
                                      >
                                        Delete
                                      </button>
                                    </div>
                                  </td>
                                </tr>
                              ))
                            )}
                          </tbody>
                        </table>
                      </div>
                    </div>
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
