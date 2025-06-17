import React, { useState } from "react";
import { useNavigate } from "react-router-dom"; // Import hook untuk navigasi
import "./ReportForm.css"; // Pastikan file ini berisi CSS yang sudah diperbaiki

const ReportForm = () => {
  const navigate = useNavigate(); // Inisialisasi hook navigasi
  const [formData, setFormData] = useState({
    namaLengkap: "",
    nomorTelepon: "",
    gedung: "",
    ruang: "",
    fasilitas: "",
    deskripsi: "",
    foto: null,
  });

  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false); // State untuk loading

  const handleChange = (e) => {
    const { name, value } = e.target;
    let processedValue = value;

    if (name === "nomorTelepon") {
      processedValue = value.replace(/[^0-9]/g, ""); // Hanya izinkan angka
    }

    setFormData({
      ...formData,
      [name]: processedValue,
    });

    if (errors[name]) {
      setErrors({ ...errors, [name]: null });
    }
  };

  const handleFileChange = (e) => {
    setFormData({
      ...formData,
      foto: e.target.files[0],
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true); // Mulai loading
    setErrors({}); // Bersihkan error lama

    const newErrors = {};
    const phoneRegex = /^\d{11,12}$/;
    if (!phoneRegex.test(formData.nomorTelepon)) {
      newErrors.nomorTelepon = "Nomor telepon harus terdiri dari 11 hingga 12 angka.";
    }

    if (Object.keys(newErrors).length > 0) {
      setErrors(newErrors);
      setLoading(false); // Hentikan loading
      return;
    }

    const formDataToSend = new FormData();
    formDataToSend.append("namaLengkap", formData.namaLengkap);
    formDataToSend.append("nomorTelepon", formData.nomorTelepon);
    formDataToSend.append("gedung", formData.gedung);
    formDataToSend.append("ruang", formData.ruang);
    formDataToSend.append("fasilitas", formData.fasilitas);
    formDataToSend.append("deskripsi", formData.deskripsi);
    if (formData.foto) {
      formDataToSend.append("foto", formData.foto);
    }

    try {
      const response = await fetch("http://localhost/Project_SBD_Kelompok_5/kirim_laporan.php", {
        method: "POST",
        body: formDataToSend,
      });

      const result = await response.json();

      if (response.ok) {
        console.log("Laporan berhasil dikirim:", result);
        alert("Laporan berhasil dikirim!");
        
        // PERUBAHAN: Arahkan kembali ke halaman utama
        navigate('/'); 

      } else {
        console.error("Gagal mengirim laporan:", result.message);
        alert("Gagal mengirim laporan: " + result.message);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("Terjadi kesalahan jaringan. Pastikan server XAMPP dan API aktif.");
    } finally {
      setLoading(false); // Selesai loading
    }
  };

  return (
    <div className="report-form-container">
      <h2>Formulir Pelaporan</h2>
      <form onSubmit={handleSubmit}>
        
        <div className="form-group">
          <label htmlFor="namaLengkap">Nama Lengkap:</label>
          <input id="namaLengkap" type="text" name="namaLengkap" value={formData.namaLengkap} onChange={handleChange} required disabled={loading}/>
        </div>

        <div className="form-group">
          <label htmlFor="nomorTelepon">Nomor Telepon:</label>
          <input 
            id="nomorTelepon" 
            type="tel"
            name="nomorTelepon" 
            value={formData.nomorTelepon} 
            onChange={handleChange} 
            maxLength="12"
            required 
            disabled={loading}
          />
          {errors.nomorTelepon && <p style={{ color: 'red', fontSize: '14px', marginTop: '5px' }}>{errors.nomorTelepon}</p>}
        </div>

        <div className="form-group">
          <label htmlFor="gedung">Gedung:</label>
          <input id="gedung" type="text" name="gedung" value={formData.gedung} onChange={handleChange} required disabled={loading}/>
        </div>

        <div className="form-group">
          <label htmlFor="ruang">Ruang:</label>
          <input id="ruang" type="text" name="ruang" value={formData.ruang} onChange={handleChange} required disabled={loading}/>
        </div>

        <div className="form-group">
          <label htmlFor="fasilitas">Fasilitas:</label>
          <input id="fasilitas" type="text" name="fasilitas" value={formData.fasilitas} onChange={handleChange} required disabled={loading}/>
        </div>

        <div className="form-group">
          <label htmlFor="deskripsi">Deskripsi:</label>
          <textarea id="deskripsi" name="deskripsi" value={formData.deskripsi} onChange={handleChange} required disabled={loading}></textarea>
        </div>

        <div className="form-group">
          <label htmlFor="foto">Upload Foto:</label>
          <input id="foto" type="file" name="foto" accept="image/*" onChange={handleFileChange} disabled={loading}/>
        </div>

        <button type="submit" disabled={loading}>
            {loading ? 'Mengirim...' : 'Kirim Laporan'}
        </button>
      </form>
    </div>
  );
};

export default ReportForm;
