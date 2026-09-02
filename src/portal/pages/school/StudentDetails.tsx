import { useEffect, useState } from "react";
import { useParams, Link, useNavigate } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface StudentDetail {
  id: number;
  name: string;
  ic?: string;
  age?: number;
  birth_cert_no?: string;
  dob?: string;
  grade?: string;
  class_name?: string;
  gender?: string;
  race?: string;
  religion?: string;
  nationality?: string;
  address?: string;
  orphan?: string;
  oku?: string;
  status?: string;
  school?: { name: string };
}

export default function StudentDetails() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [student, setStudent] = useState<StudentDetail | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let cancelled = false;
    api<StudentDetail>(`/school/students/${id}/details`)
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

  return (
    <AppLayout title="Student Details" role="school">
      <div className="container mt-4">
        <div className="bg-white p-4 rounded shadow-sm">
          {loading ? (
            <p className="text-muted">Loading…</p>
          ) : !student ? (
            <div className="alert alert-warning">Unable to load student details.</div>
          ) : (
            <>
              <h3 className="mb-3">{student.name}'s Details</h3>
              <table className="table table-bordered">
                <tbody>
                  <tr><th>Full Name</th><td>{student.name}</td></tr>
                  <tr><th>IC</th><td>{student.ic ?? "-"}</td></tr>
                  <tr><th>Age</th><td>{student.age}</td></tr>
                  <tr><th>Birth Cert No</th><td>{student.birth_cert_no}</td></tr>
                  <tr><th>Date of Birth</th><td>{student.dob}</td></tr>
                  <tr><th>Grade</th><td>{student.grade}</td></tr>
                  <tr><th>Class</th><td>{student.class_name}</td></tr>
                  <tr><th>Gender</th><td>{student.gender}</td></tr>
                  <tr><th>Race</th><td>{student.race}</td></tr>
                  <tr><th>Religion</th><td>{student.religion}</td></tr>
                  <tr><th>Nationality</th><td>{student.nationality}</td></tr>
                  <tr><th>Address</th><td>{student.address ?? "-"}</td></tr>
                  <tr><th>Orphan</th><td>{student.orphan}</td></tr>
                  <tr><th>OKU</th><td>{student.oku}</td></tr>
                  <tr>
                    <th>Status</th>
                    <td>
                      {student.status === "Active" ? (
                        <span className="badge bg-success">Active</span>
                      ) : (
                        <span className="badge bg-secondary">Inactive</span>
                      )}
                    </td>
                  </tr>
                  <tr><th>School</th><td>{student.school?.name ?? "N/A"}</td></tr>
                </tbody>
              </table>

              <hr />
              <button className="btn btn-secondary mt-3" onClick={() => navigate(-1)}>Back</button>
              <Link to={`/school/students/${id}/edit`} className="btn btn-primary mt-3 ms-2">Edit</Link>
            </>
          )}
        </div>
      </div>
    </AppLayout>
  );
}
