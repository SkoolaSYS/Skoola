import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface StudentForm {
  id: number;
  school_id: number;
  name: string;
  ic: string;
  birth_cert_no?: string;
  dob: string;
  grade: string;
  gender: string;
  race: string;
  religion: string;
  nationality: string;
  orphan: string;
  oku: string;
  address: string;
  class_name: string;
}

export default function StudentEdit() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [student, setStudent] = useState<StudentForm | null>(null);
  const [loading, setLoading] = useState(true);
  const [errors, setErrors] = useState<Record<string, string[]>>({});

  useEffect(() => {
    let cancelled = false;
    api<StudentForm>(`/school/students/${id}/edit`)
      .then((res) => {
        if (!cancelled) setStudent(res);
      })
      .catch(() => {
        if (!cancelled) setStudent(null);
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, [id]);

  function update<K extends keyof StudentForm>(key: K, value: StudentForm[K]) {
    setStudent((prev) => (prev ? { ...prev, [key]: value } : prev));
  }

  async function handleUpdate(e: React.FormEvent) {
    e.preventDefault();
    if (!student) return;
    try {
      await api(`/school/students/${id}/update`, { method: "PUT", body: student, form: true });
      navigate(`/school/dashboard`);
    } catch {
      /* errors could be set here if backend returns them */
    }
  }

  async function handleDelete() {
    if (!confirm("Are you sure you want to delete this student?")) return;
    try {
      await api(`/school/students/${id}`, { method: "DELETE" });
      navigate(`/school/dashboard`);
    } catch {
      /* ignore */
    }
  }

  if (loading) {
    return (
      <AppLayout title="Edit Student" role="school">
        <p className="text-muted">Loading…</p>
      </AppLayout>
    );
  }

  if (!student) {
    return (
      <AppLayout title="Edit Student" role="school">
        <div className="alert alert-warning">Unable to load student data.</div>
      </AppLayout>
    );
  }

  return (
    <AppLayout title="Edit Student" role="school">
      <div className="container mt-4">
        <div className="card shadow-sm">
          <div className="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 className="mb-0">Edit Student</h5>
            <a href={`/school/dashboard`} className="btn btn-light btn-sm">← Back</a>
          </div>

          <div className="card-body">
            <form onSubmit={handleUpdate}>
              <div className="mb-3">
                <label htmlFor="name" className="form-label">Full Name</label>
                <input type="text" id="name" className="form-control" value={student.name}
                  onChange={(e) => update("name", e.target.value)} required />
                {errors.name && <div className="invalid-feedback d-block">{errors.name[0]}</div>}
              </div>

              <div className="mb-3">
                <label htmlFor="ic" className="form-label">IC</label>
                <input type="text" id="ic" className="form-control" value={student.ic}
                  onChange={(e) => update("ic", e.target.value)} required />
              </div>

              <div className="mb-3">
                <label htmlFor="birth_cert_no" className="form-label">Birth Cert No</label>
                <input type="text" id="birth_cert_no" className="form-control" value={student.birth_cert_no ?? ""}
                  onChange={(e) => update("birth_cert_no", e.target.value)} />
              </div>

              <div className="mb-3">
                <label htmlFor="dob" className="form-label">Date of Birth</label>
                <input type="date" id="dob" className="form-control" value={student.dob}
                  onChange={(e) => update("dob", e.target.value)} required />
              </div>

              <div className="mb-3">
                <label htmlFor="grade" className="form-label">Grade</label>
                <select id="grade" className="form-select" value={student.grade}
                  onChange={(e) => update("grade", e.target.value)} required>
                  <option value="">-- Select Darjah/Tingkatan --</option>
                  <optgroup label="Darjah">
                    {[1, 2, 3, 4, 5].map((i) => (
                      <option key={i} value={`Darjah ${i}`}>{`Darjah ${i}`}</option>
                    ))}
                  </optgroup>
                  <optgroup label="Tingkatan">
                    {[1, 2, 3, 4, 5, 6].map((i) => (
                      <option key={i} value={`Tingkatan ${i}`}>{`Tingkatan ${i}`}</option>
                    ))}
                  </optgroup>
                </select>
              </div>

              <div className="mb-3">
                <label htmlFor="gender" className="form-label">Gender</label>
                <select id="gender" className="form-select" value={student.gender}
                  onChange={(e) => update("gender", e.target.value)} required>
                  <option value="">-- Select Gender --</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>

              <div className="mb-3">
                <label htmlFor="race" className="form-label">Race</label>
                <select id="race" className="form-select" value={student.race}
                  onChange={(e) => update("race", e.target.value)} required>
                  <option value="">-- Select Race --</option>
                  <option value="Malay">Malay</option>
                  <option value="Chinese">Chinese</option>
                  <option value="Indian">Indian</option>
                  <option value="Others">Others</option>
                </select>
              </div>

              <div className="mb-3">
                <label htmlFor="religion" className="form-label">Religion</label>
                <select id="religion" className="form-select" value={student.religion}
                  onChange={(e) => update("religion", e.target.value)} required>
                  <option value="">-- Select Religion --</option>
                  <option value="Islam">Islam</option>
                  <option value="Buddhism">Buddhism</option>
                  <option value="Hinduism">Hinduism</option>
                  <option value="Christianity">Christianity</option>
                  <option value="Others">Others</option>
                </select>
              </div>

              <div className="mb-3">
                <label htmlFor="nationality" className="form-label">Nationality</label>
                <select id="nationality" className="form-select" value={student.nationality}
                  onChange={(e) => update("nationality", e.target.value)} required>
                  <option value="">-- Select Nationality --</option>
                  <option value="Malaysian">Malaysian</option>
                  <option value="Non-Malaysian">Non-Malaysian</option>
                </select>
              </div>

              <div className="mb-3">
                <label htmlFor="orphan" className="form-label">Orphan</label>
                <select id="orphan" className="form-select" value={student.orphan}
                  onChange={(e) => update("orphan", e.target.value)} required>
                  <option value="">-- Select --</option>
                  <option value="Yes">Yes</option>
                  <option value="No">No</option>
                </select>
              </div>

              <div className="mb-3">
                <label htmlFor="oku" className="form-label">OKU</label>
                <select id="oku" className="form-select" value={student.oku}
                  onChange={(e) => update("oku", e.target.value)} required>
                  <option value="">-- Select --</option>
                  <option value="Yes">Yes</option>
                  <option value="No">No</option>
                </select>
              </div>

              <div className="mb-3">
                <label htmlFor="address" className="form-label">Address</label>
                <textarea id="address" rows={3} className="form-control" value={student.address}
                  onChange={(e) => update("address", e.target.value)} required />
              </div>

              <div className="mb-3">
                <label htmlFor="class_name" className="form-label">Class</label>
                <input type="text" id="class_name" className="form-control" value={student.class_name}
                  onChange={(e) => update("class_name", e.target.value)} required />
              </div>

              <div className="d-flex justify-content-end gap-2 mt-4">
                <button type="submit" className="btn btn-success px-4">Update</button>
                <button type="button" className="btn btn-danger px-4" onClick={handleDelete}>Delete</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
