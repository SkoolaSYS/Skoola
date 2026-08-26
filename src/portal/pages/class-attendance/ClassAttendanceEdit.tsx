import { FormEvent, useEffect, useState } from "react";
import { useNavigate, useSearchParams } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api } from "@/portal/api";

interface Student {
  id: number;
  name: string;
}

interface EditData {
  selectedGrade?: string;
  selectedClass?: string;
  selectedSubject?: string;
  students?: Student[];
  attendanceRecords?: Record<number, string>;
}

const STATUS_OPTIONS = ["Present", "Absent", "Late", "MC", "Unwell", "School Activity", "Others"];

export default function ClassAttendanceEdit() {
  const [params] = useSearchParams();
  const navigate = useNavigate();
  const grade = params.get("grade") ?? "";
  const className = params.get("class_name") ?? "";
  const subject = params.get("subject") ?? "";

  const [data, setData] = useState<EditData>({});
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    api<EditData>(`/class_attendance/edit?${params.toString()}`)
      .then((res) => {
        if (!cancelled) setData(res);
      })
      .catch(() => {
        if (!cancelled) setData({ students: [] });
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, [params]);

  const students = data.students ?? [];

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setSaving(true);
    const form = new FormData(e.currentTarget);
    const body: Record<string, string> = {
      grade: String(form.get("grade") ?? ""),
      class_name: String(form.get("class_name") ?? ""),
      subject: String(form.get("subject") ?? ""),
    };
    students.forEach((s) => {
      body[`attendance[${s.id}]`] = String(form.get(`attendance[${s.id}]`) ?? "");
    });
    try {
      await api("/class-attendance/update", { method: "POST", body, form: true });
      navigate(`/class-attendance?grade=${grade}&class_name=${className}&subject=${subject}`);
    } catch {
      /* keep on page */
    } finally {
      setSaving(false);
    }
  };

  return (
    <AppLayout title={`Edit Attendance - ${className}`} role="teacher">
      <Card>
        <h3 className="mb-5">Edit Attendance for {subject} ({className})</h3>
        {loading ? (
          <div className="text-muted">Loading…</div>
        ) : (
          <form method="POST" onSubmit={handleSubmit}>
            <input type="hidden" name="grade" value={data.selectedGrade ?? grade} />
            <input type="hidden" name="class_name" value={data.selectedClass ?? className} />
            <input type="hidden" name="subject" value={data.selectedSubject ?? subject} />

            <div className="table-responsive">
              <table className="table table-bordered table-hover mt-3 align-middle">
                <thead className="table-light">
                  <tr>
                    <th>No.</th>
                    <th>Full Name</th>
                    <th>Attendance</th>
                  </tr>
                </thead>
                <tbody>
                  {students.map((student, idx) => {
                    const status = data.attendanceRecords?.[student.id] ?? "";
                    return (
                      <tr key={student.id}>
                        <td>{idx + 1}</td>
                        <td>{student.name}</td>
                        <td>
                          <select name={`attendance[${student.id}]`} className="form-select" defaultValue={status}>
                            {STATUS_OPTIONS.map((opt) => (
                              <option key={opt} value={opt}>{opt}</option>
                            ))}
                          </select>
                        </td>
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </div>

            <button type="submit" className="btn btn-success mt-3" disabled={saving}>
              {saving ? "Updating…" : "Update"}
            </button>
          </form>
        )}
      </Card>
    </AppLayout>
  );
}
