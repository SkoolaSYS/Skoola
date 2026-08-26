import { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api } from "@/portal/api";

interface District {
  id: number;
  ppd: string;
  totalStudents?: number;
}

interface StateData {
  state?: { name: string };
  totalPelajar?: number;
  formattedAttendancePercentageState?: string;
  districts?: District[];
  state_id?: number;
}

export default function StateDashboard() {
  const { id } = useParams<{ id: string }>();
  const [data, setData] = useState<StateData>({});
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    api<StateData>(`/dashboard/state/${id ?? ""}`)
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
    <AppLayout title="State Dashboard" role="state">
      <Card>
        <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100" />
        <div className="row g-5 g-xl-10 mb-5 mb-xl-10">
          <div className="card h-xl-100 shadow-sm p-3 mb-5 bg-white rounded">
            <div className="card-header position-relative py-0 border-bottom-2 justify-content-center">
              <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
                <span className="d-flex flex-column col-md-2" />
                <span className="d-flex flex-column col-md-2">
                  <h1 className="page-heading text-dark fw-bolder fs-1 m-0 text-center">Dashboard</h1>
                  <h6 className="text-muted text-center fs-7">State Level</h6>
                </span>
                <span className="d-flex flex-column col-md-2">
                  <a href={`/dashboard/state_export/${data.state_id ?? id ?? ""}`} className="btn btn-light-success me-3">
                    <i className="ki-duotone ki-exit-up fs-2">
                      <span className="path1" />
                      <span className="path2" />
                    </i>
                    Export to Excel
                  </a>
                </span>
              </div>
            </div>
            <div className="card-body pb-3">
              <div className="row">
                <div className="d-flex flex-wrap flex-md-nowrap">
                  <div className="mx-auto">
                    <div className="card" style={{ width: "37rem" }}>
                      <div className="card-header position-relative py-0 border-bottom-2">
                        <span className="d-flex flex-column justify-content-center">
                          <h1 className="page-heading text-dark fw-bolder fs-2 m-0 text-center">
                            {loading ? "…" : data.totalPelajar ?? 0}
                          </h1>
                          <h6 className="text-muted text-center fs-7">Total Students</h6>
                        </span>
                        <span className="d-flex flex-column justify-content-center">
                          <h1 className="page-heading text-dark fw-bolder fs-2 m-0 text-center">
                            {loading ? "…" : data.formattedAttendancePercentageState ?? 0}%
                          </h1>
                          <h6 className="text-muted text-center fs-7">Average Attendance</h6>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div className="row mt-5 mb-5">
              <span className="d-flex flex-column justify-content-center">
                <h1 className="page-heading text-dark fw-bolder fs-2 m-0 text-center">List of PPD in {data.state?.name}</h1>
              </span>
            </div>

            <div className="row">
              {loading ? (
                <div className="text-muted text-center">Loading…</div>
              ) : (data.districts ?? []).length === 0 ? (
                <div className="alert alert-info text-center">No districts found.</div>
              ) : (
                (data.districts ?? []).map((district) => (
                  <div className="col-md-4 mb-4" key={district.id}>
                    <div className="d-flex flex-wrap flex-md-nowrap">
                      <div className="d-flex justify-content-between flex-column w-225px w-md-600px mx-auto mx-md-0 pt-3 pb-10">
                        <div className="mx-auto">
                          <div className="card shadow-sm p-3 mb-5 bg-white rounded" style={{ width: "20rem" }}>
                            <div className="card-body">
                              <div className="card-header justify-content-center">
                                <div className="page-heading d-flex flex-column justify-content-center text-center text-dark fw-bolder fs-4">
                                  {district.ppd}
                                </div>
                              </div>
                              <div className="card-body pb-3">
                                <div className="tab-content">
                                  <div className="tab-pane fade show active">
                                    <div className="d-flex flex-wrap flex-md-nowrap">
                                      <div className="d-flex justify-content-between flex-column w-225px w-md-600px mx-auto mx-md-0 pb-4">
                                        <div className="mx-auto">
                                          <div className="fs-5 fw-semibold">{district.totalStudents ?? 0} students</div>
                                        </div>
                                      </div>
                                    </div>
                                    <div className="d-flex flex-wrap flex-md-nowrap">
                                      <div className="d-flex justify-content-between flex-column w-225px w-md-600px mx-auto mx-md-0 pb-0">
                                        <div className="mx-auto">
                                          <Link to={`/ppd/dashboard/${district.id}`} className="btn btn-sm btn-primary align-self-center">
                                            View
                                          </Link>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                ))
              )}
            </div>
          </div>
        </div>
      </Card>
    </AppLayout>
  );
}
