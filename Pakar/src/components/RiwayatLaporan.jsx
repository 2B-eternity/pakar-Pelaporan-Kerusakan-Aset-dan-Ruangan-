import React, { useState, useEffect } from 'react';
import './RiwayatLaporan.css'; // Pastikan file CSS terhubung

const RiwayatLaporan = () => {
    const [laporan, setLaporan] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const fetchLaporan = async () => {
        try {
            const response = await fetch('http://localhost/Project_SBD_Kelompok_5/get_laporan.php');
            if (!response.ok) {
                throw new Error('Gagal mengambil data dari server');
            }
            const data = await response.json();
            setLaporan(data);
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchLaporan();
    }, []);

    // Fungsi handleUpdateStatus sudah tidak diperlukan lagi dan telah dihapus.

    if (loading) return <div className="loading-container">Memuat data laporan...</div>;
    if (error) return <div className="error-container">Error: {error}</div>;

    return (
        <div className="riwayat-container">
            <h1>Riwayat Laporan Masuk</h1>
            <div className="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pelapor</th>
                            <th>Nomor HP</th>
                            {/* --- PERUBAHAN: Kolom baru ditambahkan --- */}
                            <th>Gedung/Ruang</th>
                            <th>Fasilitas</th>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            {/* Kolom Aksi sudah dihapus */}
                        </tr>
                    </thead>
                    <tbody>
                        {laporan.length > 0 ? (
                            laporan.map(item => (
                                <tr key={item.id_laporan}>
                                    <td>{item.id_laporan}</td>
                                    <td>{item.nama_pelapor}</td>
                                    <td>{item.no_hp}</td>
                                    {/* --- PERUBAHAN: Menampilkan data di kolom baru --- */}
                                    <td>{`${item.nama_gedung} / ${item.nama_ruangan}`}</td>
                                    <td>{item.nama_fasilitas}</td>
                                    <td>{item.tanggal_laporan}</td>
                                    <td>{item.deskripsi_masalah}</td>
                                    <td><span className={`status-badge status-${item.status.replace(/\s+/g, '-').toLowerCase()}`}>{item.status}</span></td>
                                    {/* Sel untuk tombol Aksi sudah dihapus */}
                                </tr>
                            ))
                        ) : (
                            <tr>
                                {/* ColSpan disesuaikan dengan jumlah kolom baru (8) */}
                                <td colSpan="8">Tidak ada laporan yang masuk.</td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    );
};

export default RiwayatLaporan;
