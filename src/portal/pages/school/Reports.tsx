import { useEffect, useState } from "react";
import { useSearchParams } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api } from "@/portal/api";

interface ReportRecord {
  student_name?: string;
  card_id?: string;
  time?: string;
}

const MONTHS = [
  "January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December",
];

export default function Reports() {
  const [searchParams, setSearchParams] = useSearchParams();
  const [records, setRecords] = useState<ReportRecord[]>([]);
  const [loading, setLoading] = useState(true);

  const year = searchParams.get("year") ?? "";
  const month = searchParams.get("month") ?? "";
  const date = searchParams.get("date") ?? "";

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    const qs = searchParams.toString();
    api<ReportRecord[]>(`/school/reports${qs ? `?${qs}` : ""}`)
      .then((res) => {
        if (!cancelled) setRecords(res ?? []);
      })
      .catch(() => {
        if (!cancelled) setRecords([]);
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, [searchParams]);

  const currentYear = new Date().getFullYear();
  const years = [];
  for (let y = currentYear; y >= 2020; y--) years.push(y);

  function handleFilter(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    const fd = new FormData(e.currentTarget);
    const params = new URLSearchParams();
    fd.forEach((v, k) => {
      if (v) params.set(k, String(v));
    });
    setSearchParams(params);
  }

  return (
    <AppLayout title="Reports" role="school">
      <Card>
        <h3 className="mb-4">School Reports</h3>

        <form className="row g-3 mb-4" onSubmit={handleFilter}>
          <div className="col-md-3">
            <label htmlFor="year" className="form-label">Yearly</label>
            <select name="year" id="year" className="form-select" defaultValue={year}>
              <option value="">Select</option>
              {years.map((y) => (
                <option key={y} value={y}>{y}</option>
              ))}
            </select>
          </div>

          <div className="col-md-3">
            <label htmlFor="month" className="form-label">Month</label>
            <select name="month" id="month" className="form-select" defaultValue={month}>
              <option value="">Select</option>
              {MONTHS.map((m, idx) => (
                <option key={m} value={idx + 1}>{m}</option>
              ))}
            </select>
          </div>

          <div className="col-md-3">
            <label htmlFor="date" className="form-label">Date</label>
            <input type="date" name="date" id="date" className="form-control" defaultValue={date} />
          </div>

          <div className="col-md-3 d-flex align-items-end">
            <button type="submit" className="btn btn-primary">Filter</button>
            <button type="button" className="btn btn-secondary ms-2" onClick={() => setSearchParams({})}>Reset</button>
          </div>
        </form>

        <table className="table table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>Full Name</th>
              <th>Card ID</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan={4} className="text-center">Loading…</td></tr>
            ) : records.length === 0 ? (
              <tr><td colSpan={4} className="text-center">No records found.</td></tr>
            ) : (
              records.map((r, idx) => (
                <tr key={idx}>
                  <td>{idx + 1}</td>
                  <td>{r.student_name ?? "Unknown Student"}</td>
                  <td>{r.card_id}</td>
                  <td>{r.time}</td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </Card>
    </AppLayout>
  );
}
