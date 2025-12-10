// src/components/Carousel.jsx
import React, { useEffect, useRef, useState } from 'react';
import "../organismos/style/Carrousel.css";

// Ajusta rutas según dónde guardes las imágenes (src/assets o public)
import Porton from '../assets/img/CAROUCEL_P/Porton.png';
import Entrada from '../assets/img/CAROUCEL_P/Entrada.png';
import Salon from '../assets/img/CAROUCEL_P/Salon.png';
import Imagencevac from '../assets/img/CAROUCEL_P/imagencevacrelleno.jpg';

const slides = [
  {
    id: 1,
    bg: Porton,
    title: 'Centro Venezolano Alemán de Capacitación',
    name: 'CEVAC',
    des: '¡Transforma tu futuro con CEVAC!.',
    links: [
      { href: 'html/Sobre_Nosotros.html', text: 'Saber más' },
      { href: 'Formulario.php', text: 'Postúlate' }
    ]
  },
  {
    id: 2,
    bg: Entrada,
    title: 'Centro Venezolano Alemán de Capacitación',
    name: 'CEVAC',
    des: 'Innovación, formación y compromiso.',
    links: [
      { href: 'html/Sobre_Nosotros.html', text: 'Saber más' },
      { href: 'Formulario.php', text: 'Postúlate' }
    ]
  },
  {
    id: 3,
    bg: Salon,
    title: 'Centro Venezolano Alemán de Capacitación',
    name: 'CEVAC',
    des: '¡Capacitamos el talento que tu empresa necesita!.',
    links: [
      { href: 'html/Sobre_Nosotros.html', text: 'Saber más' },
      { href: 'Formulario.php', text: 'Postúlate' }
    ]
  },
  {
    id: 4,
    bg: Imagencevac,
    title: 'Centro Venezolano Alemán de Capacitación',
    name: 'CEVAC',
    des: 'Formación Dual teórica-práctica en perfecta sincronía.',
    links: [
      { href: 'html/Sobre_Nosotros.html', text: 'Saber más' },
      { href: 'Formulario.php', text: 'Postúlate' }
    ]
  }
];

export const Carousel = ({ autoplay = true, interval = 6000 }) =>{
  const [current, setCurrent] = useState(0);
  const timerRef = useRef(null);

  useEffect(() => {
    if (!autoplay) return;
    timerRef.current = setInterval(() => {
      setCurrent(prev => (prev + 1) % slides.length);
    }, interval);
    return () => clearInterval(timerRef.current);
  }, [autoplay, interval]);

  const goPrev = () => {
    clearInterval(timerRef.current);
    setCurrent(prev => (prev - 1 + slides.length) % slides.length);
  };

  const goNext = () => {
    clearInterval(timerRef.current);
    setCurrent(prev => (prev + 1) % slides.length);
  };

  return (
    <section className="carousel" id="encabezado" aria-roledescription="carousel">
      <div className="list" role="list">
        {slides.map((s, i) => {
          const itemClass = `item ${i === current ? 'active' : ''} pos-${i + 1}`;
          return (
            <div
              key={s.id}
              className={itemClass}
              role="listitem"
              aria-hidden={i === current ? 'false' : 'true'}
              style={{ backgroundImage: `url(${s.bg})` }}
            >
              <div className="content">
                <div className="title">{s.title}</div>
                <div className="name">{s.name}</div>
                <div className="des">{s.des}</div>
                <div className="btn">
                  {s.links.map((l, idx) => (
                    <button key={idx}>
                      <a href={l.href}>{l.text}</a>
                    </button>
                  ))}
                </div>
              </div>
            </div>
          );
        })}
      </div>

      <div className="arrows" aria-hidden="false">
        <button className="prev" onClick={goPrev} aria-label="Anterior">‹</button>
        <button className="next" onClick={goNext} aria-label="Siguiente">›</button>
      </div>
    </section>
  );
}
