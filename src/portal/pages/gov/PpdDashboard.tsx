import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api } from "@/portal/api";

interface School {
  id: number;
  name: string;
  totalStudents?: number;
}

interface PpdData {
  ppd?: { ppd: string };
  totalPelajarPPD?: number;
  formattedAttendancePercentagePPD?: string;
  schools?: School[];
  ppd_id?: number;
}

export default function PpdDashboard() {
  const { id } = useParams<{ id: string }>();
  const [data, setData] = useState<PpdData>({});
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    api<PpdData>(`/dashboard/ppd/${id ?? ""}`)
      .then((res) => {
        if (!cancelled) setData(res);
      })
      .catch(() => {
        if (!cancelled) setData({});
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, [id]);

  return (
    <AppLayout title="PPD Dashboard" role="ppd">
      <Card>
        <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100" />
        <div className="row g-5 g-xl-10 mb-5 mb-xl-10">
          <div className="card h-xl-100 shadow-sm p-3 mb-5 bg-white rounded">
            <div className="card-header position-relative py-0 border-bottom-2 justify-content-center">
              <span className="d-flex flex-column justify-content-center">
                <h1 className="page-heading text-dark fw-bolder fs-1 m-0 text-center">Dashboard</h1>
                <h6 className="text-muted text-center fs-7">PPD Level</h6>
              </span>
            </div>
            <div className="card-body pb-4">
              <div className="row">
                <div className="d-flex flex-wrap flex-md-nowrap pb-4">
                  <div className="mx-auto">
                    <div className="card" style={{ width: "37rem" }}>
                      <div className="card-header position-relative py-0 border-bottom-2">
                        <span className="d-flex flex-column justify-content-center">
                          <h1 className="page-heading text-dark fw-bolder fs-2 m-0 text-center">
                            {loading ? "…" : data.totalPelajarPPD ?? 0}
                          </h1>
                          <h6 className="text-muted text-center fs-7">Total Students</h6>
                        </span>
                        <span className="d-flex flex-column justify-content-center">
                          <h1 className="page-heading text-dark fw-bolder fs-2 m-0 text-center">
                            {loading ? "…" : data.formattedAttendancePercentagePPD ?? 0}%
                          </h1>
                          <h6 className="text-muted text-center fs-7">Average Attendance</h6>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div className="py-6">
              <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
                  <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
                    <h3 className="text-2xl font-semibold mb-3">LIST OF SCHOOLS IN {data.ppd?.ppd}</h3>
                  </div>
                  <a
                    href={`/dashboard/ppd_export/${data.ppd_id ?? id ?? ""}`}
                    className="btn btn-light-success me-3"
                  >
                    <i className="ki-duotone ki-exit-up fs-2">
                      <span className="path1" />
                      <span className="path2" />
                    </i>
                    Export to Excel
                  </a>
                </div>
                <div className="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                  {loading ? (
                    <div className="text-muted">Loading…</div>
                  ) : (data.schools ?? []).length === 0 ? (
                    <div className="alert alert-info text-center">No schools found.</div>
                  ) : (
                    <table className="table table-row-bordered align-middle">
                      <thead>
                        <tr className="fw-bold text-muted">
                          <th>School</th>
                          <th>Total Students</th>
                        </tr>
                      </thead>
                      <tbody>
                        {(data.schools ?? []).map((s) => (
                          <tr key={s.id}>
                            <td>{s.name}</td>
                            <td>{s.totalStudents ?? 0}</td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
      </Card>
    </AppLayout>
  );
}
