import { useEffect, useState } from "react";
import { useSearchParams, Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api } from "@/portal/api";

interface ClassOption {
  class_name: string;
  grade: { grade_name: string };
}

interface Student {
  id: number;
  name: string;
}

interface IndexData {
  grades: string[];
  classes: ClassOption[];
  selectedGrade?: string;
  selectedClass?: string;
  selectedSubject?: string;
  students?: Student[];
  attendanceRecords?: Record<number, Record<string, string>>;
  subjectOrder?: Record<string, number>;
  teacherHasAttendance?: boolean;
}

const SUBJECTS = ["Mathematics", "Science", "English", "Bahasa Melayu", "Sejarah", "Geografi"];

export default function ClassAttendanceIndex() {
  const [params, setParams] = useSearchParams();
  const [data, setData] = useState<IndexData>({ grades: [], classes: [] });
  const [loading, setLoading] = useState(true);

  const grade = params.get("grade") ?? "";
  const className = params.get("class_name") ?? "";
  const subject = params.get("subject") ?? "";

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    api<IndexData>(`/class-attendance?${params.toString()}`)
      .then((res) => {
        if (!cancelled) setData(res);
      })
      .catch(() => {
        if (!cancelled) setData({ grades: [], classes: [] });
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, [params]);

  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const form = new FormData(e.currentTarget);
    setParams({
      grade: String(form.get("grade") ?? ""),
      class_name: String(form.get("class_name") ?? ""),
      subject: String(form.get("subject") ?? ""),
    });
  };

  const students = data.students ?? [];
  const attendanceRecords = data.attendanceRecords ?? {};
  const subjectOrder = data.subjectOrder ?? {};
  const now = new Date();

  return (
    <AppLayout title="Class Attendance" role="teacher">
      <Card>
        <div className="mb-3 text-end text-muted">
          {now.toLocaleDateString("en-GB")} {now.toLocaleTimeString("en-GB", { hour: "2-digit", minute: "2-digit" })}
        </div>

        <div className="mb-5">
          <form method="GET" onSubmit={handleSubmit}>
            <div className="row g-3">
              <div className="col-md-4">
                <label htmlFor="grade" className="form-label">Grade</label>
                <select name="grade" id="grade" className="form-select" defaultValue={grade} required>
                  <option value="">-- Select Grade --</option>
                  {data.grades.map((g) => (
                    <option key={g} value={g}>{g}</option>
                  ))}
                </select>
              </div>

              <div className="col-md-4">
                <label htmlFor="class_name" className="form-label">Class</label>
                <select name="class_name" id="class_name" className="form-select" defaultValue={className} required>
                  <option value="">-- Select Class --</option>
                  {data.classes.map((c) => (
                    <option key={c.class_name} value={c.class_name} data-grade={c.grade?.grade_name}>
                      {c.class_name}
                    </option>
                  ))}
                </select>
              </div>

              <div className="col-md-4">
                <label htmlFor="subject" className="form-label">Subject</label>
                <select name="subject" id="subject" className="form-select" defaultValue={subject} required>
                  <option value="">-- Select Subject --</option>
                  {SUBJECTS.map((s) => (
                    <option key={s} value={s}>{s}</option>
                  ))}
                </select>
              </div>
            </div>

            <div className="mt-3">
              <button type="submit" className="btn btn-primary">Show Students</button>
            </div>
          </form>
        </div>

        {loading && <div className="text-muted">Loading…</div>}

        {!loading && students.length > 0 && (
          <div className="mt-5">
            <h5>Student List - {data.selectedClass} ({data.selectedGrade})</h5>

            <div className="text-end mb-3 d-flex justify-content-end gap-2">
              <Link
                to={`/class-attendance/add?grade=${data.selectedGrade}&class_name=${data.selectedClass}&subject=${data.selectedSubject}`}
                className="btn btn-primary"
              >
                Add Attendance
              </Link>

              {data.teacherHasAttendance && (
                <Link
                  to={`/class-attendance/edit?grade=${data.selectedGrade}&class_name=${data.selectedClass}&subject=${data.selectedSubject}`}
                  className="btn btn-warning"
                >
                  Edit Attendance
                </Link>
              )}

              <a
                href={`/class-attendance/pdf?grade=${data.selectedGrade}&class_name=${data.selectedClass}&subject=${data.selectedSubject}`}
                className="btn btn-danger"
                target="_blank"
                rel="noreferrer"
              >
                Download PDF
              </a>
            </div>

            <div className="table-responsive">
              <table className="table table-bordered table-hover mt-3 align-middle text-center">
                <thead className="table-light">
                  <tr>
                    <th>No.</th>
                    <th>Full Name</th>
                    {Object.entries(subjectOrder).map(([subj, num]) => (
                      <th key={subj}>{num}</th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {students.map((student, idx) => (
                    <tr key={student.id}>
                      <td>{idx + 1}</td>
                      <td>{student.name}</td>
                      {Object.keys(subjectOrder).map((subj) => {
                        const status = attendanceRecords[student.id]?.[subj];
                        const color =
                          status === "Present" ? "bg-success" : status === "Absent" ? "bg-danger" : status ? "bg-warning" : "";
                        return (
                          <td key={subj} className={color} style={{ textAlign: "center" }}>
                            {status ? status.charAt(0).toUpperCase() : "-"}
                          </td>
                        );
                      })}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}
      </Card>
    </AppLayout>
  );
}
