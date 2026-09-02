import { useEffect, useState } from "react";
import { Link, useSearchParams } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface StudentRow {
  id: number;
  name: string;
  ic: string;
  age?: number | null;
  school_id?: number | null;
  school_name?: string | null;
}

export default function StudentList() {
  const [students, setStudents] = useState<StudentRow[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchParams] = useSearchParams();
  const success = searchParams.get("success");

  useEffect(() => {
    api<{ students?: StudentRow[] }>("/student")
      .then((res) => setStudents(res.students ?? []))
      .catch(() => setStudents([]))
      .finally(() => setLoading(false));
  }, []);

  const handleDelete = async (id: number) => {
    if (!confirm("Are you sure you want to delete this student?")) return;
    try {
      await api(`/student/delete/${id}`);
      setStudents((prev) => prev.filter((s) => s.id !== id));
    } catch {
      /* ignore */
    }
  };

  return (
    <AppLayout title="Student List" role="parent">
      {success && <div className="alert alert-success">{success}</div>}

      <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
        <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
          <h1 className="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">
            Student List
          </h1>
        </div>
      </div>

      <div className="card">
        <div className="card-body p-4">
          {loading ? (
            <div className="text-center text-muted py-10">Loading…</div>
          ) : (
            <div className="table-responsive">
              <table className="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                <thead className="border-gray-200 fs-5 fw-semibold bg-lighten">
                  <tr>
                    <th>No.</th>
                    <th>Student's Name</th>
                    <th>IC</th>
                    <th>Age</th>
                    <th>School</th>
                    <th className="text-center">Action</th>
                  </tr>
                </thead>
                <tbody className="fs-6 fw-semibold text-gray-600">
                  {students.length === 0 ? (
                    <tr>
                      <td colSpan={6} className="text-center">
                        No students found.
                      </td>
                    </tr>
                  ) : (
                    students.map((student, index) => (
                      <tr key={student.id}>
                        <td>{index + 1}</td>
                        <td>{student.name}</td>
                        <td>{student.ic}</td>
                        <td>{student.age ?? "N/A"}</td>
                        <td>{student.school_name ?? "N/A"}</td>
                        <td className="text-center">
                          <Link to={`/student/edit/${student.id}`} className="btn btn-sm btn-primary me-1">
                            Edit
                          </Link>
                          <button
                            type="button"
                            className="btn btn-sm btn-danger"
                            onClick={() => handleDelete(student.id)}
                          >
                            Delete
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          )}
        </div>
      </div>
    </AppLayout>
  );
}
