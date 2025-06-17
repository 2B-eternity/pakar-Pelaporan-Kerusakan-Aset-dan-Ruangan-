import React, { useEffect } from "react";
import "./FiturUnggulan.css";
import icon from "../assets/icon-placeholder.png"; 

const fiturData = [
  {
    title: "Pelaporan Cepat",
    desc: "Kirim laporan kerusakan hanya dalam beberapa klik.",
    img: icon,
  },
  {
    title: "Kemudahan Pelaporan",
    desc: "Laporan bisa dibuat kapan saja dan di mana saja tanpa perlu datang langsung.",
    img: icon,
  },
  {
    title: "Riwayat Pelaporan",
    desc: "Pantau status laporan secara real-time dan terekam otomatis.",
    img: icon,
  },
];

const FiturUnggulan = () => {
  useEffect(() => {
  document.querySelectorAll(".fitur-card").forEach((card) => {
    card.addEventListener("mouseover", () => {
      card.style.backgroundColor = "#497248"; 
    });
    card.addEventListener("mouseout", () => {
      card.style.backgroundColor = ""; 
    });
  });

  return () => {
    document.querySelectorAll(".fitur-card").forEach((card) => {
      card.removeEventListener("mouseover", () => {});
      card.removeEventListener("mouseout", () => {});
    });
  };
}, []);


  return (
    <section className="fitur-section">
      <h2 className="fitur-heading">Fitur Unggulan</h2>
      <div className="fitur-container">
        {fiturData.map((fitur, index) => (
          <div className="fitur-card" key={index}>
            <img src={fitur.img} alt={fitur.title} className="fitur-icon" />
            <h3 className="fitur-title">{fitur.title}</h3>
            <p className="fitur-desc">{fitur.desc}</p>
          </div>
        ))}
      </div>
    </section>
  );
};

export default FiturUnggulan;
