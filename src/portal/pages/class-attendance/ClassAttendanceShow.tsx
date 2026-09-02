import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api } from "@/portal/api";

interface ClassInfo {
  subject: string;
  class: string;
  day: string;
  start: string;
  end: string;
}

interface StudentRow {
  name: string;
}

interface ShowData {
  class: ClassInfo;
  students: StudentRow[];
}

const STATUS_OPTIONS = [
  "Present",
  "Absent",
  "MC (Medical Certificate)",
  "Unwell",
  "Leave",
  "School Activity",
  "Family Matter",
  "Others",
];

export default function ClassAttendanceShow() {
  const { id } = useParams<{ id: string }>();
  const [data, setData] = useState<ShowData | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    api<ShowData>(`/class-attendance/${id}`)
      .then((res) => {
        if (!cancelled) setData(res);
      })
      .catch(() => {
        if (!cancelled) setData(null);
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, [id]);

  return (
    <AppLayout title="Class Attendance" role="teacher">
      <Card>
        {loading && <div className="text-muted">Loading…</div>}
        {!loading && !data && (
          <div className="alert alert-warning text-center">Attendance record not found.</div>
        )}
        {!loading && data && (
          <>
            <h3 className="mb-2">{data.class.subject} - {data.class.class}</h3>
            <p className="text-muted mb-1"><strong>Day:</strong> {data.class.day}</p>
            <p className="text-muted mb-4"><strong>Time:</strong> {data.class.start} - {data.class.end}</p>

            <div className="table-responsive">
              <table className="table table-bordered table-hover align-middle">
                <thead className="table-light">
                  <tr>
                    <th>Student Name</th>
                    <th>Attendance</th>
                  </tr>
                </thead>
                <tbody>
                  {data.students.map((student, idx) => (
                    <tr key={idx}>
                      <td>{student.name}</td>
                      <td>
                        <select className="form-select" defaultValue={STATUS_OPTIONS[0]}>
                          {STATUS_OPTIONS.map((opt) => (
                            <option key={opt} value={opt}>{opt}</option>
                          ))}
                        </select>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </>
        )}
      </Card>
    </AppLayout>
  );
}
