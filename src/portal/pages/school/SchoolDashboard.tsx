import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import Chart from "react-apexcharts";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api } from "@/portal/api";

interface DashboardData {
  totalPelajar: number;
  formattedAttendancePercentageSchool: number;
  school_id: number;
}

export default function SchoolDashboard() {
  const { school } = useParams<{ school?: string }>();
  const [data, setData] = useState<DashboardData | null>(null);
  const [loading, setLoading] = useState(true);
  const [successMsg, setSuccessMsg] = useState<string | null>(null);
  const [showImport, setShowImport] = useState(false);

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    api<DashboardData>(`/dashboard/school/${school ?? ""}`)
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
  }, [school]);

  const percentage = data?.formattedAttendancePercentageSchool ?? 0;
  const remaining = 100 - percentage;

  return (
    <AppLayout title="School Dashboard" role="school">
      <Card>
        <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100"></div>
        {successMsg && (
          <div className="alert alert-success alert-dismissible fade show" role="alert">
            {successMsg}
            <button type="button" className="btn-close" onClick={() => setSuccessMsg(null)}></button>
          </div>
        )}

        <div className="row g-5 g-xl-10 mb-5 mb-xl-10">
          <div className="card h-xl-100 shadow-sm p-3 mb-5 bg-white rounded">
            <div className="card-header position-relative py-0 border-bottom-2 justify-content-center">
              <span className="d-flex flex-column justify-content-center">
                <h1 className="page-heading text-dark fw-bolder fs-1 m-0 text-center">Dashboard</h1>
                <h6 className="text-muted text-center fs-7">Peringkat Sekolah</h6>
              </span>
            </div>

            <div className="card-body pb-4">
              <div className="d-flex flex-wrap justify-content-center align-items-center gap-5 pb-4 text-center">
                <div className="p-4 border-0 bg-transparent">
                  <h1 className="text-dark fw-bolder fs-1 mb-0">{loading ? "…" : data?.totalPelajar ?? 0}</h1>
                  <h6 className="text-muted fs-7">Jumlah Pelajar</h6>
                </div>

                <div className="p-4 border-0 bg-transparent">
                  <div style={{ position: "relative", width: 180, height: 180, margin: "0 auto" }}>
                    {!loading && (
                      <Chart
                        type="donut"
                        width={180}
                        height={180}
                        series={[percentage, remaining]}
                        options={{
                          chart: { type: "donut" },
                          colors: ["#4CAF50", "#E0E0E0"],
                          legend: { show: false },
                          tooltip: { enabled: false },
                          dataLabels: { enabled: false },
                          plotOptions: { pie: { donut: { size: "75%", labels: { show: true, total: { show: true, label: "", formatter: () => `${percentage}%` } } } } },
                        }}
                      />
                    )}
                  </div>
                  <h6 className="text-muted fs-7 mb-2">
                    Purata Kehadiran Tahun {new Date().getFullYear()}
                  </h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Bulk Import Modal */}
        <div className={`modal fade ${showImport ? "show d-block" : ""}`} tabIndex={-1} aria-hidden={!showImport}>
          <div className="modal-dialog">
            <form
              className="modal-content"
              onSubmit={async (e) => {
                e.preventDefault();
                const form = e.currentTarget;
                const fd = new FormData(form);
                try {
                  await api("/school/students/import", { method: "POST", body: fd });
                  setSuccessMsg("Import successful.");
                  setShowImport(false);
                } catch {
                  setSuccessMsg(null);
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
              <input type="hidden" name="school_id" value={data?.school_id ?? ""} />
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
