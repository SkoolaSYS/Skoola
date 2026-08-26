import { useEffect, useState } from "react";
import { useSearchParams } from "react-router-dom";
import Chart from "react-apexcharts";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api } from "@/portal/api";

interface Student {
  id: number;
  name: string;
}

interface Attendance {
  date: string;
  check_in?: string;
  check_out?: string;
  status: string;
  remarks?: string;
  student?: { name: string; school?: { name: string } };
}

interface AttendancePageData {
  student: Student;
  attendanceList: Attendance[];
}

type Period = "year" | "month" | "week";

export default function StudentAttendance() {
  const [searchParams] = useSearchParams();
  const studentId = searchParams.get("student") ?? "";
  const [data, setData] = useState<AttendancePageData | null>(null);
  const [loading, setLoading] = useState(true);
  const [period, setPeriod] = useState<Period>("year");

  useEffect(() => {
    let cancelled = false;
    api<AttendancePageData>(`/dashboard/student-attendance/${studentId}`)
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
  }, [studentId]);

  const series = (() => {
    if (!data) return [0, 0];
    const now = new Date();
    const start = new Date();
    if (period === "week") start.setDate(now.getDate() - 7);
    else if (period === "month") start.setMonth(now.getMonth() - 1);
    else start.setFullYear(now.getFullYear() - 1);

    const filtered = data.attendanceList.filter((a) => new Date(a.date) >= start);
    const attend = filtered.filter((a) => a.status === "attend").length;
    const absent = filtered.filter((a) => a.status === "absent").length;
    return [attend, absent];
  })();

  return (
    <AppLayout title="Dashboard" role="school">
      <Card>
        <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
          <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
            <h1 className="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">
              Dashboard {data ? `- ${data.student.name}` : ""}
            </h1>
          </div>
        </div>

        {loading ? (
          <p className="text-muted">Loading…</p>
        ) : !data ? (
          <div className="alert alert-warning">Unable to load attendance data.</div>
        ) : (
          <>
            <div className="row g-5 g-xl-10 mb-5 mb-xl-10">
              <div className="card h-xl-100">
                <div className="card-header position-relative py-0 border-bottom-2">
                  <div className="card-toolbar" data-kt-buttons="true">
                    {(["year", "month", "week"] as Period[]).map((p) => (
                      <button
                        key={p}
                        type="button"
                        className={`btn btn-sm btn-color-muted btn-active btn-active-primary px-4 me-1 ${period === p ? "active" : ""}`}
                        onClick={() => setPeriod(p)}
                      >
                        {p === "year" ? "Tahunan" : p === "month" ? "Bulanan" : "Mingguan"}
                      </button>
                    ))}
                  </div>
                </div>
                <div className="card-body pb-3">
                  <div className="d-flex flex-wrap flex-md-nowrap justify-content-center">
                    <div className="d-flex justify-content-between flex-column w-225px w-md-600px mx-auto mx-md-0 pt-3 pb-10">
                      <div className="fs-4 fw-bold text-gray-900 text-center mb-5">
                        Kehadiran {data.student.name}
                      </div>
                      <div className="mx-auto mb-4">
                        <Chart
                          type="donut"
                          width={230}
                          height={200}
                          series={series}
                          options={{
                            chart: { type: "donut" },
                            colors: ["#50cd89", "#f1416c"],
                            legend: { show: false },
                          }}
                        />
                      </div>
                      <div className="mx-auto">
                        <div className="card" style={{ width: "10rem" }}>
                          <div className="card-body">
                            <div className="d-flex align-items-center mb-2">
                              <div className="bullet bullet-dot w-8px h-7px bg-success me-2"></div>
                              <div className="fs-8 fw-semibold text-muted">Present</div>
                            </div>
                            <div className="d-flex align-items-center mb-2">
                              <div className="bullet bullet-dot w-8px h-7px bg-danger me-2"></div>
                              <div className="fs-8 fw-semibold text-muted">Absent</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div className="card">
              <div className="card-header cursor-pointer">
                <div className="card-title m-0">
                  <h3 className="fw-bold m-0">List of {data.student.name}</h3>
                </div>
              </div>
              <div className="card-body p-0">
                <div className="table-responsive">
                  <table className="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                    <thead className="border-gray-200 fs-5 fw-semibold bg-lighten">
                      <tr>
                        <th className="min-w-125px ps-9">Date</th>
                        <th className="min-w-125px ps-9">Check In</th>
                        <th className="min-w-125px ps-9">Check Out</th>
                        <th className="min-w-150px px-0">Full Name</th>
                        <th className="min-w-150px px-0">School</th>
                        <th className="min-w-150px ps-5">Status</th>
                        <th className="min-w-150px ps-5">Remarks</th>
                      </tr>
                    </thead>
                    <tbody className="fs-6 fw-semibold text-gray-600">
                      {data.attendanceList.length === 0 ? (
                        <tr><td className="text-center" colSpan={7}>No attendance records.</td></tr>
                      ) : (
                        data.attendanceList.map((a, idx) => (
                          <tr key={idx}>
                            <td className="ps-9">{a.date}</td>
                            <td className="ps-9">{a.check_in}</td>
                            <td className="ps-9">{a.check_out}</td>
                            <td className="ps-0">{a.student?.name}</td>
                            <td className="ps-0">{a.student?.school?.name}</td>
                            <td className="text-center">
                              {a.status === "attend" ? (
                                <a className="badge status-badge" style={{ backgroundColor: "#50cd89" }}>Present</a>
                              ) : (
                                <a className="badge status-badge" style={{ backgroundColor: "#f1416c" }}>Absent</a>
                              )}
                            </td>
                            <td className="ps-9">{a.remarks ?? "-"}</td>
                          </tr>
                        ))
                      )}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </>
        )}
      </Card>
    </AppLayout>
  );
}
