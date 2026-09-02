import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface ParentInfo {
  id: number;
  name: string;
  ic?: string | null;
  address?: string | null;
}

interface AttendanceRow {
  id: number;
  date: string;
  check_in?: string | null;
  check_out?: string | null;
  status: string;
  remarks?: string | null;
  student?: {
    name?: string;
    grade?: string;
    class_name?: string;
    school?: { name?: string };
  };
}

interface DashboardResponse {
  parent: ParentInfo;
  students: Record<string, string>;
  attendanceList: AttendanceRow[];
}

export default function Dashboard() {
  const [data, setData] = useState<DashboardResponse | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    api<DashboardResponse>("/parent/dashboard")
      .then(setData)
      .catch(() => setData(null))
      .finally(() => setLoading(false));
  }, []);

  const parent = data?.parent;
  const students = data?.students ?? {};
  const attendanceList = data?.attendanceList ?? [];
  const studentEntries = Object.entries(students);

  return (
    <AppLayout title="Dashboard" role="parent">
      <div className="card mb-5 mb-xl-10">
        <div className="card-body">
          {parent && parent.ic == null && parent.address == null && (
            <div className="alert alert-warning">
              Please complete your profile details.
            </div>
          )}

          <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
            <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
              <div className="mb-3 text-center" />
              <h1 className="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">
                Dashboard
              </h1>
            </div>
          </div>

          <div className="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div className="card h-xl-100">
              <div className="card-body pb-3">
                <div className="tab-content">
                  <div className="tab-pane fade show active">
                    <div className="d-flex flex-wrap flex-md-nowrap">
                      {loading ? (
                        <span className="text-center text-muted">Loading…</span>
                      ) : studentEntries.length === 0 ? (
                        <span className="text-center text-muted">
                          You have not registered any students.{" "}
                          <Link to="/student/create"> Add students</Link>
                        </span>
                      ) : (
                        studentEntries.map(([id, name]) => (
                          <div
                            key={id}
                            className="d-flex justify-content-between flex-column w-225px w-md-600px mx-auto mx-md-0 pt-3 pb-10"
                          >
                            <div className="fs-4 fw-bold text-gray-900 text-center mb-5">
                              Attendance for {name}
                              <br />
                            </div>
                            <div className="mx-auto mb-4" id={`pie_chart_${id}`} />
                            <div className="mx-auto">
                              <div className="card" style={{ width: "10rem" }}>
                                <div className="card-body">
                                  <div className="d-flex align-items-center mb-2">
                                    <div
                                      className="bullet bullet-dot w-8px h-7px bg-success me-2"
                                      style={{ color: "#50cd89" }}
                                    />
                                    <div className="fs-8 fw-semibold text-muted">Present</div>
                                  </div>
                                  <div className="d-flex align-items-center mb-2">
                                    <div
                                      className="bullet bullet-dot w-8px h-7px bg-danger me-2"
                                      style={{ color: "#f1416c" }}
                                    />
                                    <div className="fs-8 fw-semibold text-muted">Absent</div>
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
              </div>
            </div>
          </div>

          <div className="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div className="card h-xl-100">
              <div className="card-header position-relative py-0 border-bottom-2">
                <ul className="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3"></ul>
              </div>
              <div className="card">
                <div className="card-header cursor-pointer">
                  <div className="card-title m-0">
                    <h3 className="fw-bold m-0">Attendance list</h3>
                  </div>
                  <Link to="/attendance" className="btn btn-sm btn-primary align-self-center">
                    View more
                  </Link>
                </div>
                <div id="kt_referred_users_tab_content" className="tab-content">
                  <div
                    id="kt_referrals_1"
                    className="card-body p-0 tab-pane fade show active"
                    role="tabpanel"
                  >
                    <div className="table-responsive">
                      <table className="table align-middle table-row-bordered table-row-solid gy-4 gs-9 text-nowrap">
                        <thead className="border-gray-200 fs-5 fw-semibold bg-lighten">
                          <tr>
                            <th className="ps-4">Date</th>
                            <th className="ps-4">Check In</th>
                            <th className="ps-4">Check Out</th>
                            <th className="ps-4">Name</th>
                            <th className="ps-4">School</th>
                            <th className="ps-4">Grade</th>
                            <th className="ps-4">Class</th>
                            <th className="ps-4">Status</th>
                            <th className="ps-4">Remarks</th>
                          </tr>
                        </thead>
                        <tbody className="fs-6 fw-semibold text-gray-600">
                          {attendanceList.length === 0 ? (
                            <tr>
                              <td className="text-center" colSpan={8}>
                                No attendance records.
                              </td>
                            </tr>
                          ) : (
                            attendanceList.map((attendance) => (
                              <tr key={attendance.id}>
                                <td className="ps-9">{attendance.date}</td>
                                <td className="ps-9">{attendance.check_in}</td>
                                <td className="ps-9">{attendance.check_out}</td>
                                <td className="ps-0">{attendance.student?.name ?? "-"}</td>
                                <td className="ps-0">{attendance.student?.school?.name ?? "-"}</td>
                                <td className="ps-0">{attendance.student?.grade ?? "-"}</td>
                                <td className="ps-0">{attendance.student?.class_name ?? "-"}</td>
                                {attendance.status === "attend" ? (
                                  <td className="text-center">
                                    <a className="badge status-badge" style={{ backgroundColor: "#50cd89" }}>
                                      Present
                                    </a>
                                  </td>
                                ) : (
                                  <td className="text-center">
                                    <a className="badge status-badge" style={{ backgroundColor: "#f1416c" }}>
                                      Absent
                                    </a>
                                  </td>
                                )}
                                <td className="ps-9">
                                  {attendance.status === "absent" ? (
                                    <div className="d-flex flex-column">
                                      <span>{attendance.remarks ?? "-"}</span>
                                      <Link
                                        to={`/attendance/edit-remarks/${attendance.id}`}
                                        className="text-primary fw-semibold mt-1"
                                        style={{ fontSize: "12px" }}
                                      >
                                        Edit
                                      </Link>
                                    </div>
                                  ) : (
                                    "-"
                                  )}
                                </td>
                              </tr>
                            ))
                          )}
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
