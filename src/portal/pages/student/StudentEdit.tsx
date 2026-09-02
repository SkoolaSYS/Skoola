import { useEffect, useState } from "react";
import { useNavigate, useParams, Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api, ApiError } from "@/portal/api";

interface StudentRecord {
  id: number;
  name: string;
  ic: string;
  birth_cert_no?: string | null;
  dob?: string | null;
  grade?: string | null;
  class_name?: string | null;
  gender?: string | null;
  race?: string | null;
  religion?: string | null;
  nationality?: string | null;
  orphan?: string | null;
  address?: string | null;
  oku?: string | null;
  state_id?: number | null;
  district_id?: number | null;
  school_id?: number | null;
}

interface EditResponse {
  student: StudentRecord;
}

function calcAge(dob: string): string {
  if (!dob) return "";
  const d = new Date(dob);
  if (isNaN(d.getTime())) return "";
  const today = new Date();
  let age = today.getFullYear() - d.getFullYear();
  const m = today.getMonth() - d.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < d.getDate())) age--;
  return String(age);
}

export default function StudentEdit() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [student, setStudent] = useState<StudentRecord | null>(null);
  const [loading, setLoading] = useState(true);
  const [errors, setErrors] = useState<string[]>([]);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    api<EditResponse>(`/student/edit/${id}`)
      .then((res) => setStudent(res.student))
      .catch(() => setStudent(null))
      .finally(() => setLoading(false));
  }, [id]);

  const update = (field: keyof StudentRecord, value: string) => {
    setStudent((prev) => (prev ? { ...prev, [field]: value } : prev));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!student) return;
    setSaving(true);
    setErrors([]);
    try {
      await api(`/student/update/${id}`, {
        method: "POST",
        form: true,
        body: {
          name: student.name,
          ic: student.ic,
          birth_cert_no: student.birth_cert_no ?? "",
          dob: student.dob ?? "",
          grade: student.grade ?? "",
          class_name: student.class_name ?? "",
          gender: student.gender ?? "",
          race: student.race ?? "",
          religion: student.religion ?? "",
          nationality: student.nationality ?? "",
          orphan: student.orphan ?? "",
          address: student.address ?? "",
          oku: student.oku ?? "",
          state: student.state_id ?? "",
          district: student.district_id ?? "",
          school: student.school_id ?? "",
        },
      });
      navigate("/student");
    } catch (err) {
      if (err instanceof ApiError && err.errors) {
        setErrors(Object.values(err.errors).flat());
      } else {
        setErrors(["Unable to update student."]);
      }
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return (
      <AppLayout title="Edit Student Profile" role="parent">
        <div className="card">
          <div className="card-body text-center text-muted py-10">Loading…</div>
        </div>
      </AppLayout>
    );
  }

  return (
    <AppLayout title="Edit Student Profile" role="parent">
      <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
        <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
          <h1 className="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">
            Student List
          </h1>
        </div>
      </div>

      <div className="card mb-5 mb-xl-10">
        <div className="card-header border-0">
          <div className="card-title m-0">
            <h3 className="fw-bold m-0">Student Profile Details</h3>
          </div>
        </div>

        {!student ? (
          <div className="card-body">
            <div className="alert alert-warning">Unable to load student details.</div>
          </div>
        ) : (
          <div id="kt_account_settings_profile_details" className="collapse show">
            <form id="kt_account_profile_details_form" className="form" onSubmit={handleSubmit}>
              {errors.length > 0 && (
                <div className="alert alert-danger">
                  <ul className="mb-0">
                    {errors.map((error, i) => (
                      <li key={i}>{error}</li>
                    ))}
                  </ul>
                </div>
              )}

              <div className="card-body border-top p-9">
                {/* Full Name */}
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
                  <div className="col-lg-8 fv-row">
                    <input
                      type="text"
                      className="form-control form-control-lg form-control-solid"
                      value={student.name}
                      onChange={(e) => update("name", e.target.value)}
                    />
                  </div>
                </div>

                {/* IC */}
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label required fw-semibold fs-6">IC</label>
                  <div className="col-lg-8 fv-row">
                    <input
                      type="text"
                      className="form-control form-control-lg form-control-solid"
                      value={student.ic}
                      onChange={(e) => update("ic", e.target.value)}
                    />
                  </div>
                </div>

                {/* Birth Cert No */}
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label fw-semibold fs-6">Birth Certificate No.</label>
                  <div className="col-lg-8 fv-row">
                    <input
                      type="text"
                      className="form-control form-control-lg form-control-solid"
                      value={student.birth_cert_no ?? ""}
                      onChange={(e) => update("birth_cert_no", e.target.value)}
                    />
                  </div>
                </div>

                {/* DOB + Age */}
                <div className="row mb-6 align-items-center">
                  <label className="col-lg-4 col-form-label required fw-semibold fs-6">Date of Birth</label>
                  <div className="col-lg-4 fv-row">
                    <input
                      type="date"
                      id="dob"
                      className="form-control form-control-lg form-control-solid"
                      value={student.dob ?? ""}
                      onChange={(e) => update("dob", e.target.value)}
                    />
                  </div>
                  <div className="col-lg-4 d-flex align-items-center">
                    <label className="me-2 fw-semibold fs-6">Age:</label>
                    <input
                      type="text"
                      id="age"
                      className="form-control form-control-lg form-control-solid w-25"
                      value={calcAge(student.dob ?? "")}
                      readOnly
                    />
                  </div>
                </div>

                {/* Grade */}
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label fw-semibold fs-6">Grade</label>
                  <div className="col-lg-8 fv-row">
                    <select
                      className="form-select form-select-lg form-select-solid"
                      value={student.grade ?? ""}
                      onChange={(e) => update("grade", e.target.value)}
                    >
                      <option value="">-- Select --</option>
                      {[1, 2, 3, 4, 5, 6].map((i) => (
                        <option key={`darjah-${i}`} value={`Darjah ${i}`}>
                          Darjah {i}
                        </option>
                      ))}
                      {[1, 2, 3, 4, 5].map((i) => (
                        <option key={`tingkatan-${i}`} value={`Tingkatan ${i}`}>
                          Tingkatan {i}
                        </option>
                      ))}
                    </select>
                  </div>
                </div>

                {/* Class */}
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label fw-semibold fs-6">Class</label>
                  <div className="col-lg-8 fv-row">
                    <input
                      type="text"
                      className="form-control form-control-lg form-control-solid"
                      value={student.class_name ?? ""}
                      onChange={(e) => update("class_name", e.target.value)}
                    />
                  </div>
                </div>

                {/* Gender */}
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label fw-semibold fs-6">Gender</label>
                  <div className="col-lg-8 fv-row">
                    <select
                      className="form-select form-select-lg form-select-solid"
                      value={student.gender ?? ""}
                      onChange={(e) => update("gender", e.target.value)}
                    >
                      <option value="">-- Select --</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                  </div>
                </div>

                {/* Race */}
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label fw-semibold fs-6">Race</label>
                  <div className="col-lg-8 fv-row">
                    <select
                      className="form-select form-select-lg form-select-solid"
                      value={student.race ?? ""}
                      onChange={(e) => update("race", e.target.value)}
                    >
                      <option value="">-- Select --</option>
                      {["Melayu", "Chinese", "Indian", "Bumiputera Sabah", "Bumiputera Sarawak", "Orang Asli", "Others"].map(
                        (race) => (
                          <option key={race} value={race}>
                            {race}
                          </option>
                        )
                      )}
                    </select>
                  </div>
                </div>

                {/* Religion */}
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label fw-semibold fs-6">Religion</label>
                  <div className="col-lg-8 fv-row">
                    <select
                      className="form-select form-select-lg form-select-solid"
                      value={student.religion ?? ""}
                      onChange={(e) => update("religion", e.target.value)}
                    >
                      <option value="">-- Select --</option>
                      {["Islam", "Christianity", "Buddhism", "Hinduism", "Sikhism", "Taoism", "Others"].map((religion) => (
                        <option key={religion} value={religion}>
                          {religion}
                        </option>
                      ))}
                    </select>
                  </div>
                </div>

                {/* Nationality */}
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label fw-semibold fs-6">Nationality</label>
                  <div className="col-lg-8 fv-row">
                    <select
                      className="form-select form-select-lg form-select-solid"
                      value={student.nationality ?? ""}
                      onChange={(e) => update("nationality", e.target.value)}
                    >
                      <option value="">-- Select --</option>
                      <option value="Malaysian">Malaysian</option>
                      <option value="Non-Malaysian">Non-Malaysian</option>
                    </select>
                  </div>
                </div>

                {/* Orphan */}
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label fw-semibold fs-6">Orphan</label>
                  <div className="col-lg-8 fv-row">
                    <select
                      className="form-select form-select-lg form-select-solid"
                      value={student.orphan ?? ""}
                      onChange={(e) => update("orphan", e.target.value)}
                    >
                      <option value="">-- Select --</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                </div>

                {/* Address */}
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label fw-semibold fs-6">Address</label>
                  <div className="col-lg-8 fv-row">
                    <textarea
                      className="form-control form-control-lg form-control-solid"
                      value={student.address ?? ""}
                      onChange={(e) => update("address", e.target.value)}
                    />
                  </div>
                </div>

                {/* OKU */}
                <div className="row mb-6">
                  <label className="col-lg-4 col-form-label fw-semibold fs-6">OKU</label>
                  <div className="col-lg-8 fv-row">
                    <select
                      className="form-select form-select-lg form-select-solid"
                      value={student.oku ?? ""}
                      onChange={(e) => update("oku", e.target.value)}
                    >
                      <option value="">-- Select --</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                </div>
              </div>

              <div className="card-footer d-flex justify-content-end py-6 px-9">
                <Link to="/student" className="btn btn-light btn-active-light-primary me-2">
                  Cancel
                </Link>
                <button
                  type="submit"
                  className="btn btn-primary"
                  id="kt_account_profile_details_submit"
                  disabled={saving}
                >
                  {saving ? "Saving..." : "Save"}
                </button>
              </div>
            </form>
          </div>
        )}
      </div>
    </AppLayout>
  );
}
