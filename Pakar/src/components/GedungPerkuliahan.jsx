import React from "react";
import "./GedungPerkuliahan.css";
import gedungImage from "../assets/kampus.png";

const gedungList = ["A", "B", "C", "D", "E", "F", "G", "H", "I","j"];

const GedungPerkuliahan = () => {
  return (
    <section className="gedung-section">
      <h2 className="gedung-heading">Gedung Perkuliahan</h2>
      <div className="gedung-container-wrapper">
        <div className="gedung-container">
          {gedungList.map((gedung, index) => (
            <div className="gedung-box" key={index}>
              <img src={gedungImage} alt={`Gedung ${gedung}`} className="gedung-image" />
              <p className="gedung-name">Gedung {gedung}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default GedungPerkuliahan;