import { FormEvent, useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface AttendanceDetail {
  date?: string | null;
  checkin?: string | null;
  checkout?: string | null;
  name?: string | null;
  school?: string | null;
  status?: string | null;
  remarks?: string | null;
  remarksInput?: string | null;
}

export default function EditRemarks() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [data, setData] = useState<AttendanceDetail | null>(null);
  const [loading, setLoading] = useState(true);
  const [remarks, setRemarks] = useState("all");
  const [remarksInput, setRemarksInput] = useState("");
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    api<AttendanceDetail>(`/attendance/edit-remarks/${id}`)
      .then((res) => {
        setData(res);
        setRemarks(res.remarks ?? "all");
        setRemarksInput(res.remarksInput ?? "");
      })
      .catch(() => setData(null))
      .finally(() => setLoading(false));
  }, [id]);

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setSaving(true);
    try {
      await api(`/attendance/edit-remarks/${id}`, {
        method: "POST",
        form: true,
        body: { remarks, remarksInput },
      });
      navigate("/attendance");
    } catch {
      /* ignore */
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return (
      <AppLayout title="Edit Remarks" role="parent">
        <div className="card">
          <div className="card-body text-center text-muted py-10">Loading…</div>
        </div>
      </AppLayout>
    );
  }

  return (
    <AppLayout title="Edit Remarks" role="parent">
      <div className="card mb-5 mb-xl-10">
        <div className="card-header border-0">
          <div className="card-title m-0">
            <h3 className="fw-bold m-0">Edit Remarks</h3>
          </div>
        </div>

        <div id="kt_account_settings_profile_details" className="collapse show">
          <form id="kt_account_profile_details_form" className="form" onSubmit={handleSubmit}>
            <div className="card-body border-top p-9">
              <div className="row mb-6">
                <label className="col-lg-4 col-form-label fw-semibold fs-6">Date</label>
                <div className="col-lg-8 fv-row">
                  <div className="row">
                    <input
                      type="text"
                      className="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                      placeholder="Date"
                      value={data?.date ?? ""}
                      readOnly
                    />
                  </div>
                </div>
              </div>

              <div className="row mb-6">
                <label className="col-lg-4 col-form-label fw-semibold fs-6">Time</label>
                <div className="col-lg-8">
                  <div className="row">
                    <div className="col-lg-6 fv-row">
                      <input
                        type="text"
                        className="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                        placeholder="Check-In"
                        value={data?.checkin ?? ""}
                        readOnly
                      />
                    </div>
                    <div className="col-lg-6 fv-row">
                      <input
                        type="text"
                        className="form-control form-control-lg form-control-solid"
                        placeholder="Check Out"
                        value={data?.checkout ?? ""}
                        readOnly
                      />
                    </div>
                  </div>
                </div>
              </div>

              <div className="row mb-6">
                <label className="col-lg-4 col-form-label fw-semibold fs-6">Student's Name</label>
                <div className="col-lg-8 fv-row">
                  <input
                    type="text"
                    className="form-control form-control-lg form-control-solid"
                    placeholder="Name"
                    value={data?.name ?? ""}
                    readOnly
                  />
                </div>
              </div>

              <div className="row mb-6">
                <label className="col-lg-4 col-form-label fw-semibold fs-6">School</label>
                <div className="col-lg-8 fv-row">
                  <input
                    type="text"
                    className="form-control form-control-lg form-control-solid"
                    placeholder="School"
                    value={data?.school ?? ""}
                    readOnly
                  />
                </div>
              </div>

              <div className="row mb-6">
                <label className="col-lg-4 col-form-label fw-semibold fs-6">Status</label>
                <div className="col-lg-8 fv-row">
                  <input
                    type="text"
                    className="form-control form-control-lg form-control-solid"
                    placeholder="Status"
                    value={data?.status ?? ""}
                    readOnly
                  />
                </div>
              </div>

              <div className="row mb-6">
                <label className="col-lg-4 col-form-label fw-semibold fs-6">Remarks</label>
                <div className="col-lg-8 fv-row">
                  <label className="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                    <input
                      className="form-check-input"
                      type="radio"
                      name="remarks"
                      value="all"
                      checked={remarks === "all" && remarksInput === ""}
                      onChange={() => {
                        setRemarks("all");
                      }}
                    />
                    <span className="form-check-label text-gray-600">Sick</span>
                  </label>
                  <label className="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                    <input
                      className="form-check-input"
                      type="radio"
                      name="remarks"
                      value="all"
                      checked={false}
                      onChange={() => setRemarks("all")}
                    />
                    <span className="form-check-label text-gray-600">Family Matters</span>
                  </label>
                  <label className="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                    <input
                      className="form-check-input"
                      type="radio"
                      name="remarks"
                      value="all"
                      checked={false}
                      onChange={() => setRemarks("all")}
                    />
                    <span className="form-check-label text-gray-600">Late</span>
                  </label>
                  <label className="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                    <input
                      className="form-check-input"
                      type="radio"
                      name="remarks"
                      value="others"
                      checked={remarks === "others"}
                      onChange={() => setRemarks("others")}
                    />
                    <span className="form-check-label text-gray-600">Others</span>
                  </label>
                  <div className="col-lg-8 fv-row">
                    {remarks === "others" && (
                      <div id="remarksField">
                        <label htmlFor="remarksInput"></label>
                        <textarea
                          className="form-control form-control-lg form-control-solid"
                          id="remarksInput"
                          value={remarksInput}
                          onChange={(e) => setRemarksInput(e.target.value)}
                        />
                      </div>
                    )}
                  </div>
                </div>
              </div>
            </div>

            <div className="card-footer d-flex justify-content-end py-6 px-9">
              <button type="reset" className="btn btn-light btn-active-light-primary me-2">
                Discard
              </button>
              <button
                type="submit"
                className="btn btn-primary"
                id="kt_account_profile_details_submit"
                disabled={saving}
              >
                {saving ? "Saving..." : "Save Changes"}
              </button>
            </div>
          </form>
        </div>
      </div>
    </AppLayout>
  );
}
