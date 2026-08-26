import Chart from "react-apexcharts";
import { AppLayout } from "../layouts/AppLayout";
import { Card } from "../components/Card";

type Period = "daily" | "weekly" | "monthly";

/** [present, absent, others] per period — shape returned by DashboardController */
type AttendanceData = Record<string, Record<Period, number[]> | null>;

const PERIODS: Period[] = ["daily", "weekly", "monthly"];

const LABELS: Record<Period, string> = {
  daily: "Harian",
  weekly: "Mingguan",
  monthly: "Bulanan",
};

// Stubbed API payload (replaces the Blade @json($attendanceData))
const classes: Record<string, string> = {
  "1 Amanah": "Tahun 1",
  "2 Bestari": "Tahun 2",
};

const attendanceData: AttendanceData = {
  "1 Amanah": {
    daily: [28, 3, 1],
    weekly: [132, 12, 6],
    monthly: [540, 48, 22],
  },
  "2 Bestari": null,
};

function DonutChart({ series }: { series: number[] }) {
  return (
    <Chart
      type="donut"
      height={250}
      series={series}
      options={{
        chart: { type: "donut" },
        colors: ["#50cd89", "#f1416c", "#f5d70f"],
        legend: { show: true, position: "bottom" },
        plotOptions: { pie: { customScale: 0.9 } },
        labels: ["Hadir", "Tidak Hadir", "Lain-lain"],
        responsive: [
          { breakpoint: 1024, options: { chart: { height: 220 } } },
          { breakpoint: 768, options: { chart: { height: 200 } } },
          { breakpoint: 480, options: { chart: { height: 180 } } },
        ],
      }}
    />
  );
}

export default function TeacherDashboard() {
  const noClassAssigned = Object.keys(classes).length === 0;

  return (
    <AppLayout title="Dashboard" role="teacher">
      <Card>
        <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
          <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
            <h1 className="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">
              Dashboard Guru
            </h1>
          </div>
        </div>

        {noClassAssigned && (
          <div className="alert alert-warning text-center">No class assigned.</div>
        )}

        {Object.entries(classes).map(([className, gradeName]) => {
          const data = attendanceData[className];
          return (
            <div key={className}>
              <h4 className="fw-semibold text-dark mb-4">
                {gradeName} - {className}
              </h4>

              {data ? (
                <div className="row g-3 justify-content-center">
                  {PERIODS.map((period) => (
                    <div
                      key={period}
                      className="col-lg-4 col-md-6 col-12 text-center mb-4"
                    >
                      <h6 className="mb-2 text-muted text-uppercase">
                        {LABELS[period]}
                      </h6>
                      <div className="attendance-chart">
                        <DonutChart series={data[period] ?? [0, 0, 0]} />
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="alert alert-info text-center">
                  Attendance not yet recorded for this class.
                </div>
              )}
            </div>
          );
        })}
      </Card>
    </AppLayout>
  );
}
