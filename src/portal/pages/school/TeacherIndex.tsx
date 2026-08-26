import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface Teacher {
  id: number;
  name: string;
  email: string;
  status: string;
  ic?: string;
  address?: string;
  phone_num?: string;
  classes?: { class_name: string; grade?: { grade_name: string } }[];
}

export default function TeacherIndex() {
  const [teachers, setTeachers] = useState<Teacher[]>([]);
  const [loading, setLoading] = useState(true);
  const [successMsg, setSuccessMsg] = useState<string | null>(null);
  const [modalTeacher, setModalTeacher] = useState<Teacher | null>(null);

  function loadTeachers() {
    setLoading(true);
    api<Teacher[]>("/school/teachers")
      .then((res) => setTeachers(res ?? []))
      .catch(() => setTeachers([]))
      .finally(() => setLoading(false));
  }

  useEffect(() => {
    loadTeachers();
  }, []);

  async function toggleStatus(teacher: Teacher) {
    try {
      await api(`/school/teachers/${teacher.id}/toggle`, { method: "PUT" });
      setSuccessMsg("Status updated.");
      loadTeachers();
    } catch {
      /* ignore */
    }
  }

  return (
    <AppLayout title="Teachers" role="school">
      <div className="container mt-4">
        {successMsg && <div className="alert alert-success">{successMsg}</div>}

        <div className="card">
          <div className="card-body">
            <div className="d-flex justify-content-between align-items-center mb-3">
              <h4 className="card-title mb-0">Teachers</h4>
              <Link to="/school/teachers/create" className="btn btn-primary">Add Teacher</Link>
            </div>

            {loading ? (
              <p className="text-muted">Loading…</p>
            ) : teachers.length === 0 ? (
              <div className="alert alert-info">No teachers found.</div>
            ) : (
              <div className="table-responsive">
                <table className="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Full Name</th>
                      <th>Email</th>
                      <th>Status</th>
                      <th className="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    {teachers.map((teacher, index) => (
                      <tr key={teacher.id}>
                        <td>{index + 1}</td>
                        <td>{teacher.name}</td>
                        <td>{teacher.email}</td>
                        <td>
                          {teacher.status === "active" ? (
                            <span className="badge bg-success">Active</span>
                          ) : teacher.status === "inactive" ? (
                            <span className="badge bg-secondary">Inactive</span>
                          ) : (
                            <span className="badge bg-light text-muted">—</span>
                          )}
                        </td>
                        <td className="text-center">
                          <button className="btn btn-sm btn-primary me-1" onClick={() => setModalTeacher(teacher)}>
                            View
                          </button>
                          <Link to={`/school/teachers/${teacher.id}/edit`} className="btn btn-sm btn-warning me-1">
                            Edit
                          </Link>
                          <button
                            className={`btn btn-sm ${teacher.status === "active" ? "btn-danger" : "btn-success"}`}
                            onClick={() => toggleStatus(teacher)}
                          >
                            {teacher.status === "active" ? "Deactivate" : "Activate"}
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        </div>
      </div>

      <div className={`modal fade ${modalTeacher ? "show d-block" : ""}`} tabIndex={-1} aria-hidden={!modalTeacher}>
        <div className="modal-dialog modal-dialog-centered">
          <div className="modal-content">
            <div className="modal-header bg-primary text-white">
              <h5 className="modal-title">Teacher Details</h5>
              <button type="button" className="btn-close btn-close-white" onClick={() => setModalTeacher(null)}></button>
            </div>
            {modalTeacher && (
              <div className="modal-body">
                <p><strong>Full Name:</strong> {modalTeacher.name}</p>
                <p><strong>IC:</strong> {modalTeacher.ic ?? "-"}</p>
                <p><strong>Address:</strong> {modalTeacher.address ?? "-"}</p>
                <p><strong>Phone Number:</strong> {modalTeacher.phone_num ?? "-"}</p>
                <p><strong>Email:</strong> {modalTeacher.email}</p>
                <p><strong>Assigned Classes:</strong> {
                  modalTeacher.classes && modalTeacher.classes.length > 0
                    ? modalTeacher.classes.map((c) => `${c.grade?.grade_name ?? ""} ${c.class_name}`).join(", ")
                    : "-"
                }</p>
                <p><strong>Status:</strong> {modalTeacher.status ?? "N/A"}</p>
              </div>
            )}
            <div className="modal-footer">
              <button type="button" className="btn btn-secondary" onClick={() => setModalTeacher(null)}>Close</button>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
