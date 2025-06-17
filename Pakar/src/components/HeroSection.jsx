import React from "react";
import { Link } from "react-router-dom";
import "./HeroSection.css";
import kampusImg from "../assets/kampus.png"; 
import logoImg from "../assets/logo.png";

const HeroSection = () => {
  return (
    <div className="hero">
      <nav className="navbar">
        <img src={logoImg} alt="Logo" className="logo" />
        <ul className="nav-links">
          <li><Link to="/riwayat">Riwayat Laporan</Link></li>
        </ul>
      </nav>

      <div className="hero-content">
        <div className="hero-text">
          <h1>PAKAR!</h1>
          <h3>Pelaporan Kerusakan Aset dan Ruangan</h3>
          <p>
            Jaga kenyamanan bersama! <br />
            Laporkan kerusakan aset dan ruangan melalui PAKAR sekarang juga.
          </p>
          <Link to="/login" className="report-button">Buat Pengaduan →</Link>
        </div>

        <div className="hero-image">
          <img src={kampusImg} alt="Gedung Kampus" />
        </div>
      </div>
    </div>
  );
};

export default HeroSection;
