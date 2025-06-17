import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import "./LoginPage.css"; 

const LoginPage = () => {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({ email: "", password: "" });
    
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        if (!formData.email || !formData.password) {
            setError("Email dan password harus diisi.");
            return;
        }

        setLoading(true); 
        setError(""); 

        try {
           const response = await fetch("http://localhost/Project_SBD_Kelompok_5/test_koneksi.php", { 
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData),
            });

            const result = await response.json(); 

            if (response.ok) {
                console.log("Login Berhasil:", result);
                localStorage.setItem("userEmail", result.user.email); 
                navigate("/report"); 
            } else {
                
                setError(result.message || "Login Gagal. Silakan coba lagi.");
            }
        } catch (err) {
           
            console.error("Error:", err);
            setError("Terjadi kesalahan jaringan. Pastikan server API aktif.");
        } finally {
            setLoading(false); 
        }
    };

    return (
        <div className="login-container">
            <div className="login-form">
                <h2>Masuk</h2>
                <form onSubmit={handleSubmit}>
                    <label>Email:</label>
                    {/* Input untuk Email */}
                    <input 
                        type="email" 
                        name="email" 
                        value={formData.email} 
                        onChange={handleChange} 
                        required 
                        disabled={loading} 
                    />

                    {/* Input untuk Password (YANG HILANG) */}
                    <label>Password:</label>
                    <input 
                        type="password" 
                        name="password" 
                        value={formData.password} 
                        onChange={handleChange} 
                        required 
                        disabled={loading} 
                    />
                    
                    {/* Tampilkan pesan error jika ada */}
                    {error && <p className="error-message">{error}</p>}

                    {/* Ubah teks tombol saat loading */}
                    <button type="submit" disabled={loading}>
                        {loading ? 'Memproses...' : 'Masuk'}
                    </button>
                </form>
            </div>

            <div className="login-info">
                <h3>Selamat Datang di PAKAR!</h3>
                <p>Platform pelaporan kerusakan aset dan ruangan untuk menjaga kenyamanan lingkungan. 
                Laporkan masalah dengan mudah dan pastikan fasilitas tetap optimal.</p>
            </div>
        </div>
    );
};

export default LoginPage;
