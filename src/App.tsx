import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { AuthProvider } from "./portal/auth";

import Login from "./portal/pages/Login";
import TeacherDashboard from "./portal/pages/TeacherDashboard";

import Register from "./portal/pages/auth/Register";
import ForgotPassword from "./portal/pages/auth/ForgotPassword";
import ResetPassword from "./portal/pages/auth/ResetPassword";
import ConfirmPassword from "./portal/pages/auth/ConfirmPassword";
import VerifyEmail from "./portal/pages/auth/VerifyEmail";
import TwoFactorChallenge from "./portal/pages/auth/TwoFactorChallenge";
import SchoolLogin from "./portal/pages/auth/SchoolLogin";

import ParentDashboard from "./portal/pages/parent/Dashboard";
import ParentProfile from "./portal/pages/parent/Profile";
import ParentEditProfile from "./portal/pages/parent/EditProfile";
import ParentAttendance from "./portal/pages/parent/Attendance";
import ParentEditRemarks from "./portal/pages/parent/EditRemarks";
import GuardianAdd from "./portal/pages/parent/GuardianAdd";
import GuardianEdit from "./portal/pages/parent/GuardianEdit";
import GuardianShow from "./portal/pages/parent/GuardianShow";

import StudentIndex from "./portal/pages/student/StudentIndex";
import StudentList from "./portal/pages/student/StudentList";
import StudentAdd from "./portal/pages/student/StudentAdd";
import StudentEditPage from "./portal/pages/student/StudentEdit";

import ClassAttendanceIndex from "./portal/pages/class-attendance/ClassAttendanceIndex";
import ClassAttendanceAdd from "./portal/pages/class-attendance/ClassAttendanceAdd";
import ClassAttendanceEdit from "./portal/pages/class-attendance/ClassAttendanceEdit";
import ClassAttendanceShow from "./portal/pages/class-attendance/ClassAttendanceShow";

import SchoolDashboard from "./portal/pages/school/SchoolDashboard";
import ClassManagement from "./portal/pages/school/ClassManagement";
import EditClass from "./portal/pages/school/EditClass";
import SchoolReports from "./portal/pages/school/Reports";
import SchoolStudentManagement from "./portal/pages/school/StudentManagement";
import SchoolStudentCreate from "./portal/pages/school/StudentCreate";
import SchoolStudentEdit from "./portal/pages/school/StudentEdit";
import SchoolStudentDetails from "./portal/pages/school/StudentDetails";
import SchoolStudentAttendance from "./portal/pages/school/StudentAttendance";
import TeacherIndex from "./portal/pages/school/TeacherIndex";
import TeacherCreate from "./portal/pages/school/TeacherCreate";
import TeacherEdit from "./portal/pages/school/TeacherEdit";

import AdminUsers from "./portal/pages/admin/AdminUsers";
import AdminAddUser from "./portal/pages/admin/AdminAddUser";
import AdminEditUser from "./portal/pages/admin/AdminEditUser";

import PpdDashboard from "./portal/pages/gov/PpdDashboard";
import StateDashboard from "./portal/pages/gov/StateDashboard";
import CountryDashboard from "./portal/pages/gov/CountryDashboard";

import Welcome from "./portal/pages/misc/Welcome";
import Terms from "./portal/pages/misc/Terms";
import Policy from "./portal/pages/misc/Policy";

const App = () => (
  <BrowserRouter>
    <AuthProvider>
      <Routes>
        {/* Public / auth */}
        <Route path="/login" element={<Login />} />
        <Route path="/school-login" element={<SchoolLogin />} />
        <Route path="/register" element={<Register />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />
        <Route path="/reset-password" element={<ResetPassword />} />
        <Route path="/reset-password/:token" element={<ResetPassword />} />
        <Route path="/confirm-password" element={<ConfirmPassword />} />
        <Route path="/verify-email" element={<VerifyEmail />} />
        <Route path="/two-factor-challenge" element={<TwoFactorChallenge />} />

        {/* Misc public */}
        <Route path="/welcome" element={<Welcome />} />
        <Route path="/terms" element={<Terms />} />
        <Route path="/policy" element={<Policy />} />

        {/* Teacher */}
        <Route path="/dashboard" element={<TeacherDashboard />} />

        {/* Parent */}
        <Route path="/parent/dashboard" element={<ParentDashboard />} />
        <Route path="/parent/profile" element={<ParentProfile />} />
        <Route path="/parent/profile/edit" element={<ParentEditProfile />} />
        <Route path="/parent/attendance" element={<ParentAttendance />} />
        <Route path="/parent/remarks/edit" element={<ParentEditRemarks />} />
        <Route path="/parent/guardian/add" element={<GuardianAdd />} />
        <Route path="/parent/guardian/:id/edit" element={<GuardianEdit />} />
        <Route path="/parent/guardian/:id" element={<GuardianShow />} />

        {/* Students */}
        <Route path="/student" element={<StudentIndex />} />
        <Route path="/student/list" element={<StudentList />} />
        <Route path="/student/add" element={<StudentAdd />} />
        <Route path="/student/:id/edit" element={<StudentEditPage />} />

        {/* Class attendance */}
        <Route path="/class-attendance" element={<ClassAttendanceIndex />} />
        <Route path="/class-attendance/add" element={<ClassAttendanceAdd />} />
        <Route path="/class-attendance/:id/edit" element={<ClassAttendanceEdit />} />
        <Route path="/class-attendance/:id" element={<ClassAttendanceShow />} />

        {/* School */}
        <Route path="/school/dashboard" element={<SchoolDashboard />} />
        <Route path="/school/classes" element={<ClassManagement />} />
        <Route path="/school/classes/:id/edit" element={<EditClass />} />
        <Route path="/school/reports" element={<SchoolReports />} />
        <Route path="/school/students" element={<SchoolStudentManagement />} />
        <Route path="/school/students/create" element={<SchoolStudentCreate />} />
        <Route path="/school/students/:id/edit" element={<SchoolStudentEdit />} />
        <Route path="/school/students/:id" element={<SchoolStudentDetails />} />
        <Route path="/school/students/:id/attendance" element={<SchoolStudentAttendance />} />
        <Route path="/school/teachers" element={<TeacherIndex />} />
        <Route path="/school/teachers/create" element={<TeacherCreate />} />
        <Route path="/school/teachers/:id/edit" element={<TeacherEdit />} />

        {/* Admin */}
        <Route path="/admin" element={<AdminUsers />} />
        <Route path="/admin/users" element={<AdminUsers />} />
        <Route path="/admin/users/add" element={<AdminAddUser />} />
        <Route path="/admin/users/:id/edit" element={<AdminEditUser />} />

        {/* Government */}
        <Route path="/ppd/dashboard" element={<PpdDashboard />} />
        <Route path="/state/dashboard" element={<StateDashboard />} />
        <Route path="/country/dashboard" element={<CountryDashboard />} />

        {/* Legacy redirects */}
        <Route path="/portal/dashboard" element={<Navigate to="/dashboard" replace />} />
        <Route path="/portal" element={<Navigate to="/dashboard" replace />} />
        <Route path="/" element={<Navigate to="/login" replace />} />
        <Route path="*" element={<Navigate to="/login" replace />} />
      </Routes>
    </AuthProvider>
  </BrowserRouter>
);

export default App;
