import { FormEvent, useEffect, useMemo, useState } from "react";
import { useNavigate, useSearchParams } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api } from "@/portal/api";

interface Student {
  id: number;
  name: string;
}

interface AddData {
  selectedGrade?: string;
  selectedClass?: string;
  selectedSubject?: string;
  students?: Student[];
  existingAttendance?: Record<number, string>;
}

const STATUS_OPTIONS = ["Present", "Absent", "Late", "MC", "Unwell", "School Activity", "Others"];

export default function ClassAttendanceAdd() {
  const [params] = useSearchParams();
  const navigate = useNavigate();
  const grade = params.get("grade") ?? "";
  const className = params.get("class_name") ?? "";
  const subject = params.get("subject") ?? "";

  const [data, setData] = useState<AddData>({});
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [search, setSearch] = useState("");

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    api<AddData>(`/class_attendance/add?${params.toString()}`)
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
  const filtered = useMemo(
    () => students.filter((s) => s.name.toLowerCase().includes(search.toLowerCase())),
    [students, search]
  );

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
      body[`attendance[${s.id}]`] = String(form.get(`attendance[${s.id}]`) ?? "Present");
    });
    try {
      await api("/class-attendance/save", { method: "POST", body, form: true });
      navigate(`/class-attendance?grade=${grade}&class_name=${className}&subject=${subject}`);
    } catch {
      /* keep on page; backend may be unreachable in preview */
    } finally {
      setSaving(false);
    }
  };

  return (
    <AppLayout title={`Add Attendance - ${className}`} role="teacher">
      <Card>
        <h3 className="mb-5">Add Attendance for {className}</h3>
        {loading ? (
          <div className="text-muted">Loading…</div>
        ) : (
          <form method="POST" onSubmit={handleSubmit}>
            <input type="hidden" name="grade" value={data.selectedGrade ?? grade} />
            <input type="hidden" name="class_name" value={data.selectedClass ?? className} />
            <input type="hidden" name="subject" value={data.selectedSubject ?? subject} />

            <div className="mb-3 d-flex justify-content-end">
              <input
                type="text"
                id="searchInput"
                className="form-control w-auto me-2"
                placeholder="Search student"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
              <button type="button" className="btn btn-primary" id="searchButton">Search</button>
            </div>

            <div className="table-responsive">
              <table className="table table-bordered table-hover mt-3 align-middle" id="attendanceTable">
                <thead className="table-light">
                  <tr>
                    <th>No.</th>
                    <th>Full Name</th>
                    <th>Attendance</th>
                  </tr>
                </thead>
                <tbody>
                  {filtered.map((student, idx) => {
                    const currentStatus = data.existingAttendance?.[student.id] ?? "Present";
                    return (
                      <tr key={student.id}>
                        <td>{idx + 1}</td>
                        <td>{student.name}</td>
                        <td>
                          <select name={`attendance[${student.id}]`} className="form-select" defaultValue={currentStatus}>
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
              {saving ? "Saving…" : "Save"}
            </button>
          </form>
        )}
      </Card>
    </AppLayout>
  );
}
