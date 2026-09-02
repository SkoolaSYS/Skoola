import { useState } from "react";
import { GuestLayout } from "../../layouts/GuestLayout";
import { api } from "../../api";

export default function VerifyEmail() {
  const [sent, setSent] = useState(false);
  const [submitting, setSubmitting] = useState(false);

  const resend = async () => {
    setSubmitting(true);
    try {
      await api("/email/verification-notification", { method: "POST", form: true });
      setSent(true);
    } catch {
      setSent(false);
    } finally {
      setSubmitting(false);
    }
  };

  const logout = async () => {
    try {
      await api("/logout", { method: "POST", form: true });
    } finally {
      window.location.href = "/login";
    }
  };

  return (
    <GuestLayout title="Verify Email">
      <div className="text-center mb-11">
        <h1 className="text-dark fw-bolder mb-3">Verify Email</h1>
      </div>

      <div className="text-gray-500 fw-semibold fs-6 mb-4">
        Before continuing, could you verify your email address by clicking on the link we just
        emailed to you? If you didn't receive the email, we will gladly send you another.
      </div>

      {sent && (
        <div className="alert alert-success">
          A new verification link has been sent to the email address you provided in your profile
          settings.
        </div>
      )}

      <div className="d-flex align-items-center justify-content-between mt-4">
        <button className="btn btn-primary" onClick={resend} disabled={submitting}>
          {submitting ? "Please wait..." : "Resend Verification Email"}
        </button>

        <div>
          <a href="/profile" className="link-primary fs-6 me-3">
            Edit Profile
          </a>
          <button type="button" className="btn btn-light-danger" onClick={logout}>
            Log Out
          </button>
        </div>
      </div>
    </GuestLayout>
  );
}
