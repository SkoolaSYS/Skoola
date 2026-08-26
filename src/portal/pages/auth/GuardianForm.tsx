export interface GuardianFormValue {
  name: string;
  username: string;
  email: string;
  phone_num: string;
  ic: string;
  relationship: string;
  occupation: string;
  address: string;
  password: string;
  password_confirmation: string;
}

interface GuardianFormProps {
  index: number;
  value: GuardianFormValue;
  onChange: (index: number, field: keyof GuardianFormValue, value: string) => void;
  onRemove: (index: number) => void;
}

/** Mirrors auth/_guardian-form.blade.php */
export default function GuardianForm({ index, value, onChange, onRemove }: GuardianFormProps) {
  const field = (name: keyof GuardianFormValue) => `guardians[${index}][${name}]`;

  return (
    <div className="guardian-form-wrapper mb-4">
      <div className="guardian-form mb-4">
        <h4>Guardian {index + 1}</h4>

        <div className="row mb-6">
          <label className="col-lg-4 col-form-label">Full Name</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={field("name")}
              className="form-control"
              placeholder="Full Name"
              value={value.name}
              onChange={(e) => onChange(index, "name", e.target.value)}
            />
          </div>
        </div>

        <div className="row mb-6">
          <label className="col-lg-4 col-form-label">Username</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={field("username")}
              className="form-control"
              placeholder="Username"
              value={value.username}
              onChange={(e) => onChange(index, "username", e.target.value)}
            />
          </div>
        </div>

        <div className="row mb-6">
          <label className="col-lg-4 col-form-label">Email</label>
          <div className="col-lg-8 fv-row">
            <input
              type="email"
              name={field("email")}
              className="form-control"
              placeholder="Email"
              value={value.email}
              onChange={(e) => onChange(index, "email", e.target.value)}
            />
          </div>
        </div>

        <div className="row mb-6">
          <label className="col-lg-4 col-form-label">Phone Number</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={field("phone_num")}
              className="form-control"
              value={value.phone_num}
              onChange={(e) => onChange(index, "phone_num", e.target.value)}
            />
          </div>
        </div>

        <div className="row mb-6">
          <label className="col-lg-4 col-form-label">IC / Passport</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={field("ic")}
              className="form-control"
              value={value.ic}
              onChange={(e) => onChange(index, "ic", e.target.value)}
            />
          </div>
        </div>

        <div className="row mb-6">
          <label className="col-lg-4 col-form-label">Relationship</label>
          <div className="col-lg-8 fv-row">
            <select
              name={field("relationship")}
              className="form-control"
              value={value.relationship}
              onChange={(e) => onChange(index, "relationship", e.target.value)}
            >
              <option value="">Select Relationship</option>
              <option value="father">Father</option>
              <option value="mother">Mother</option>
              <option value="guardian">Guardian</option>
            </select>
          </div>
        </div>

        <div className="row mb-6">
          <label className="col-lg-4 col-form-label">Occupation</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={field("occupation")}
              className="form-control"
              value={value.occupation}
              onChange={(e) => onChange(index, "occupation", e.target.value)}
            />
          </div>
        </div>

        <div className="row mb-6">
          <label className="col-lg-4 col-form-label">Address</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={field("address")}
              className="form-control"
              value={value.address}
              onChange={(e) => onChange(index, "address", e.target.value)}
            />
          </div>
        </div>

        <div className="row mb-6" data-kt-password-meter="true">
          <label className="col-lg-4 col-form-label">Password</label>
          <div className="col-lg-8 fv-row">
            <div className="position-relative mb-3">
              <input
                type="password"
                name={field("password")}
                className="form-control bg-transparent"
                required
                autoComplete="new-password"
                value={value.password}
                onChange={(e) => onChange(index, "password", e.target.value)}
              />
            </div>
            <div className="text-muted">Use 8 or more characters with a mix of letters, numbers &amp; symbols.</div>
          </div>
        </div>

        <div className="row mb-6">
          <label className="col-lg-4 col-form-label">Repeat Password</label>
          <div className="col-lg-8 fv-row">
            <input
              type="password"
              name={field("password_confirmation")}
              className="form-control bg-transparent"
              required
              autoComplete="new-password"
              value={value.password_confirmation}
              onChange={(e) => onChange(index, "password_confirmation", e.target.value)}
            />
          </div>
        </div>
      </div>

      <button
        type="button"
        className="btn btn-danger remove-guardian-btn mt-2 mb-2 w-100"
        onClick={() => onRemove(index)}
      >
        Remove
      </button>
    </div>
  );
}
