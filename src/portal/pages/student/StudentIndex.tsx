import { FormEvent, useState } from "react";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface StudentRow {
  id: number;
  name: string;
  grade: string;
  class_name: string;
}

interface RemarkRow {
  id: number;
  type: string;
  text: string;
}

export default function StudentIndex() {
  const [grade, setGrade] = useState("");
  const [className, setClassName] = useState("");
  const [students, setStudents] = useState<StudentRow[]>([]);
  const [remarks, setRemarks] = useState<Record<number, RemarkRow>>({});
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState<string | null>(null);

  const handleFilter = async (e: FormEvent) => {
    e.preventDefault();
    setLoading(true);
    try {
      const res = await api<{ students?: StudentRow[] }>(
        `/students/filter?grade=${encodeURIComponent(grade)}&class_name=${encodeURIComponent(className)}`
      );
      setStudents(res.students ?? []);
    } catch {
      setStudents([]);
    } finally {
      setLoading(false);
    }
  };

  const updateRemark = (id: number, field: "type" | "text", value: string) => {
    setRemarks((prev) => ({
      ...prev,
      [id]: { id, type: prev[id]?.type ?? "", text: prev[id]?.text ?? "", [field]: value },
    }));
  };

  const handleSave = async (e: FormEvent) => {
    e.preventDefault();
    const body: Record<string, string> = {};
    Object.values(remarks).forEach((r) => {
      body[`remarks[${r.id}][type]`] = r.type;
      body[`remarks[${r.id}][text]`] = r.text;
    });
    try {
      await api("/students/remarks", { method: "POST", body, form: true });
      setMessage("Remarks saved successfully");
    } catch {
      setMessage("Unable to save remarks.");
    }
  };

  return (
    <AppLayout title="Students" role="teacher">
      <div className="card">
        <div className="card-header">
          <h3 className="card-title">Students Movement / Remarks</h3>
        </div>
        <div className="card-body">
          <div className="mb-3 text-end text-muted">{new Date().toLocaleString()}</div>

          {message && (
            <div className="alert alert-success alert-dismissible fade show" role="alert">
              {message}
              <button type="button" className="btn-close" onClick={() => setMessage(null)}></button>
            </div>
          )}

          <form className="row g-3 mb-5" onSubmit={handleFilter}>
            <div className="col-md-4">
              <label className="form-label">Grade</label>
              <input
                type="text"
                className="form-control"
                value={grade}
                onChange={(e) => setGrade(e.target.value)}
              />
            </div>
            <div className="col-md-4">
              <label className="form-label">Class</label>
              <input
                type="text"
                className="form-control"
                value={className}
                onChange={(e) => setClassName(e.target.value)}
              />
            </div>
            <div className="col-md-4 d-flex align-items-end">
              <button type="submit" className="btn btn-primary" disabled={loading}>
                {loading ? "Loading..." : "Filter"}
              </button>
            </div>
          </form>

          <div className="mt-4">
            <form onSubmit={handleSave}>
              <table className="table table-bordered text-center">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Full Name</th>
                    <th>Reason</th>
                    <th>Details</th>
                  </tr>
                </thead>
                <tbody>
                  {students.length === 0 ? (
                    <tr>
                      <td colSpan={4} className="text-muted">
                        No students found.
                      </td>
                    </tr>
                  ) : (
                    students.map((student, index) => (
                      <tr key={student.id}>
                        <td>{index + 1}</td>
                        <td className="text-start">
                          {student.name}
                          <br />
                          <small className="text-muted">
                            {student.grade} - {student.class_name}
                          </small>
                        </td>
                        <td>
                          <select
                            className="form-select"
                            value={remarks[student.id]?.type ?? ""}
                            onChange={(e) => updateRemark(student.id, "type", e.target.value)}
                          >
                            <option value="">-- Pilih --</option>
                            <option value="school activity">Aktiviti Sekolah</option>
                            <option value="clinic">Klinik</option>
                            <option value="others">Lain Lain</option>
                          </select>
                        </td>
                        <td>
                          <input
                            type="text"
                            className="form-control"
                            placeholder="Optional justification"
                            value={remarks[student.id]?.text ?? ""}
                            onChange={(e) => updateRemark(student.id, "text", e.target.value)}
                          />
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>

              <div className="text-end mt-3">
                <button className="btn btn-success" type="submit">
                  Save
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
