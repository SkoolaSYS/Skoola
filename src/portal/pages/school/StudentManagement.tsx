import { useEffect, useState } from "react";
import { useParams, Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api } from "@/portal/api";

interface Student {
  id: number;
  name: string;
  ic?: string;
  grade?: string;
  class_name?: string;
  status?: string;
}

interface School {
  id: number;
  name: string;
}

interface ManagementData {
  school: School;
  students: Student[];
}

export default function StudentManagement() {
  const { school: schoolId } = useParams<{ school: string }>();
  const [school, setSchool] = useState<School | null>(null);
  const [students, setStudents] = useState<Student[]>([]);
  const [loading, setLoading] = useState(true);
  const [successMsg, setSuccessMsg] = useState<string | null>(null);
  const [showImport, setShowImport] = useState(false);

  useEffect(() => {
    let cancelled = false;
    api<ManagementData>(`/dashboard/school/${schoolId}/students/management`)
      .then((res) => {
        if (cancelled) return;
        setSchool(res.school ?? null);
        setStudents(res.students ?? []);
      })
      .catch(() => {
        if (!cancelled) {
          setSchool(null);
          setStudents([]);
        }
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, [schoolId]);

  return (
    <AppLayout title="Student Management" role="school">
      <Card>
        <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
          <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
            <h3 className="text-2xl font-semibold mb-3">
              List of Students {school ? `- ${school.name}` : ""}
            </h3>
          </div>

          <div className="d-flex align-items-center gap-2">
            <div className="dropdown">
              <button
                className="btn btn-light-primary dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <i className="ki-duotone ki-plus fs-2"></i> Add Student
              </button>
              <ul className="dropdown-menu">
                <li>
                  <Link className="dropdown-item" to="/school/students/create">Add Manually</Link>
                </li>
                <li>
                  <a className="dropdown-item" href="#" onClick={(e) => { e.preventDefault(); setShowImport(true); }}>
                    Import Excel
                  </a>
                </li>
              </ul>
            </div>

            <a href={`/dashboard/school_export/${schoolId}`} className="btn btn-light-success">
              <i className="ki-duotone ki-exit-up fs-2"></i> Export
            </a>
          </div>

          {successMsg && (
            <div className="alert alert-success alert-dismissible fade show mt-3" role="alert">
              {successMsg}
              <button type="button" className="btn-close" onClick={() => setSuccessMsg(null)}></button>
            </div>
          )}
        </div>

        <div className="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
          {loading ? (
            <p className="text-muted">Loading…</p>
          ) : students.length === 0 ? (
            <div className="alert alert-info">No students found.</div>
          ) : (
            <div className="table-responsive">
              <table className="table table-row-bordered gy-4">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>IC</th>
                    <th>Grade</th>
                    <th>Class</th>
                    <th>Status</th>
                    <th className="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  {students.map((s, idx) => (
                    <tr key={s.id}>
                      <td>{idx + 1}</td>
                      <td>{s.name}</td>
                      <td>{s.ic ?? "-"}</td>
                      <td>{s.grade ?? "-"}</td>
                      <td>{s.class_name ?? "-"}</td>
                      <td>{s.status ?? "-"}</td>
                      <td className="text-center">
                        <Link to={`/school/students/${s.id}`} className="btn btn-sm btn-primary me-1">View</Link>
                        <Link to={`/school/students/${s.id}/edit`} className="btn btn-sm btn-warning">Edit</Link>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
        </div>

        <div className={`modal fade ${showImport ? "show d-block" : ""}`} tabIndex={-1} aria-hidden={!showImport}>
          <div className="modal-dialog">
            <form
              className="modal-content"
              onSubmit={async (e) => {
                e.preventDefault();
                const fd = new FormData(e.currentTarget);
                try {
                  await api("/school/students/import", { method: "POST", body: fd });
                  setSuccessMsg("Import successful.");
                  setShowImport(false);
                } catch {
                  /* ignore */
                }
              }}
            >
              <div className="modal-header">
                <h5 className="modal-title">Import Students From Excel</h5>
                <button type="button" className="btn-close" onClick={() => setShowImport(false)}></button>
              </div>
              <div className="modal-body">
                <p className="mb-3">Please use the provided template.</p>
                <a href="/school/students/template" className="btn btn-link">Download Template</a>
                <div className="mb-3">
                  <label htmlFor="import_file" className="form-label">Upload Excel File</label>
                  <input type="file" className="form-control" id="import_file" name="import_file" accept=".xlsx, .xls" required />
                </div>
              </div>
              <input type="hidden" name="school_id" value={schoolId} />
              <div className="modal-footer">
                <button type="submit" className="btn btn-primary">Import</button>
                <button type="button" className="btn btn-secondary" onClick={() => setShowImport(false)}>Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </Card>
    </AppLayout>
  );
}
