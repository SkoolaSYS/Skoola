import { ChangeEvent } from "react";

export interface StudentFormData {
  name: string;
  birth_cert_no: string;
  ic: string;
  dob: string;
  grade: string;
  class_name: string;
  gender: string;
  race: string;
  religion: string;
  nationality: string;
  orphan: string;
  address: string;
  oku: string;
  state: string;
  district: string;
  school: string;
}

export function emptyStudentForm(): StudentFormData {
  return {
    name: "",
    birth_cert_no: "",
    ic: "",
    dob: "",
    grade: "",
    class_name: "",
    gender: "",
    race: "",
    religion: "",
    nationality: "",
    orphan: "",
    address: "",
    oku: "",
    state: "",
    district: "",
    school: "",
  };
}

interface StudentFormProps {
  prefix: string;
  data: StudentFormData;
  onChange: (field: keyof StudentFormData, value: string) => void;
}

export default function StudentForm({ prefix, data, onChange }: StudentFormProps) {
  const handle = (field: keyof StudentFormData) => (
    e: ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>
  ) => onChange(field, e.target.value);

  return (
    <div className="student-form">
      <div className="card-body border-top p-9">
        {/* Full Name */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={`${prefix}[name]`}
              className="form-control form-control-lg form-control-solid"
              placeholder="Full Name"
              value={data.name}
              onChange={handle("name")}
            />
          </div>
        </div>

        {/* Birth Cert No */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">Birth Certificate No.</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={`${prefix}[birth_cert_no]`}
              className="form-control form-control-lg form-control-solid"
              placeholder="Birth Certificate No."
              value={data.birth_cert_no}
              onChange={handle("birth_cert_no")}
            />
          </div>
        </div>

        {/* IC */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">IC</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={`${prefix}[ic]`}
              className="form-control form-control-lg form-control-solid"
              placeholder="IC"
              value={data.ic}
              onChange={handle("ic")}
            />
          </div>
        </div>

        {/* DOB */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">Date of Birth</label>
          <div className="col-lg-8 fv-row">
            <input
              type="date"
              name={`${prefix}[dob]`}
              className="form-control form-control-lg form-control-solid dob-input"
              value={data.dob}
              onChange={handle("dob")}
            />
          </div>
        </div>

        {/* Grade */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">Grade</label>
          <div className="col-lg-8 fv-row">
            <select
              name={`${prefix}[grade]`}
              className="form-control form-control-lg form-control-solid"
              value={data.grade}
              onChange={handle("grade")}
            >
              <option value="">Select Grade</option>
              <option value="Tingkatan 1">Tingkatan 1</option>
              <option value="Tingkatan 2">Tingkatan 2</option>
              <option value="Tingkatan 3">Tingkatan 3</option>
              <option value="Tingkatan 4">Tingkatan 4</option>
              <option value="Tingkatan 5">Tingkatan 5</option>
              <option value="Tingkatan 6">Tingkatan 6</option>
              <option value="Darjah 1">Darjah 1</option>
              <option value="Darjah 2">Darjah 2</option>
              <option value="Darjah 3">Darjah 3</option>
              <option value="Darjah 4">Darjah 4</option>
              <option value="Darjah 5">Darjah 5</option>
              <option value="Darjah 6">Darjah 6</option>
            </select>
          </div>
        </div>

        {/* Class */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">Class</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={`${prefix}[class_name]`}
              className="form-control form-control-lg form-control-solid"
              placeholder="Class"
              value={data.class_name}
              onChange={handle("class_name")}
            />
          </div>
        </div>

        {/* Gender */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">Gender</label>
          <div className="col-lg-8 fv-row">
            <select
              name={`${prefix}[gender]`}
              className="form-control form-control-lg form-control-solid"
              value={data.gender}
              onChange={handle("gender")}
            >
              <option value="">Select Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
        </div>

        {/* Race */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">Race</label>
          <div className="col-lg-8 fv-row">
            <select
              name={`${prefix}[race]`}
              className="form-control form-control-lg form-control-solid"
              value={data.race}
              onChange={handle("race")}
            >
              <option value="">Select Race</option>
              <option value="Melayu">Melayu</option>
              <option value="Chinese">Chinese</option>
              <option value="Indian">Indian</option>
              <option value="Bumiputera Sabah">Bumiputera Sabah</option>
              <option value="Bumiputera Sarawak">Bumiputera Sarawak</option>
              <option value="Orang Asli">Orang Asli</option>
              <option value="Others">Others</option>
            </select>
          </div>
        </div>

        {/* Religion */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">Religion</label>
          <div className="col-lg-8 fv-row">
            <select
              name={`${prefix}[religion]`}
              className="form-control form-control-lg form-control-solid"
              value={data.religion}
              onChange={handle("religion")}
            >
              <option value="">Select Religion</option>
              <option value="Islam">Islam</option>
              <option value="Christianity">Christianity</option>
              <option value="Buddhism">Buddhism</option>
              <option value="Hinduism">Hinduism</option>
              <option value="Sikhism">Sikhism</option>
              <option value="Taoism">Taoism</option>
              <option value="Others">Others</option>
            </select>
          </div>
        </div>

        {/* Nationality */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">Nationality</label>
          <div className="col-lg-8 fv-row">
            <select
              name={`${prefix}[nationality]`}
              className="form-control form-control-lg form-control-solid"
              value={data.nationality}
              onChange={handle("nationality")}
            >
              <option value="">Select Nationality</option>
              <option value="Malaysian">Malaysian</option>
              <option value="Non-Malaysian">Non-Malaysian</option>
            </select>
          </div>
        </div>

        {/* Orphan */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">Orphan</label>
          <div className="col-lg-8 fv-row">
            <select
              name={`${prefix}[orphan]`}
              className="form-control form-control-lg form-control-solid"
              value={data.orphan}
              onChange={handle("orphan")}
            >
              <option value="">Select</option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>
        </div>

        {/* Address */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">Address</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={`${prefix}[address]`}
              className="form-control form-control-lg form-control-solid"
              placeholder="Address"
              value={data.address}
              onChange={handle("address")}
            />
          </div>
        </div>

        {/* OKU */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">OKU</label>
          <div className="col-lg-8 fv-row">
            <select
              name={`${prefix}[oku]`}
              className="form-control form-control-lg form-control-solid"
              value={data.oku}
              onChange={handle("oku")}
            >
              <option value="">Select</option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>
        </div>

        <h3>School Details</h3>

        {/* State / District / School (PPD dropdown) */}
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">State</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={`${prefix}[state]`}
              className="form-control form-control-lg form-control-solid"
              placeholder="State ID"
              value={data.state}
              onChange={handle("state")}
            />
          </div>
        </div>
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">District</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={`${prefix}[district]`}
              className="form-control form-control-lg form-control-solid"
              placeholder="District ID"
              value={data.district}
              onChange={handle("district")}
            />
          </div>
        </div>
        <div className="row mb-6">
          <label className="col-lg-4 col-form-label fw-semibold fs-6">School</label>
          <div className="col-lg-8 fv-row">
            <input
              type="text"
              name={`${prefix}[school]`}
              className="form-control form-control-lg form-control-solid"
              placeholder="School ID"
              value={data.school}
              onChange={handle("school")}
            />
          </div>
        </div>
      </div>
    </div>
  );
}
