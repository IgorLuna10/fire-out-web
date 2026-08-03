/* ═══════════════════════════════════════════════════
   FIRE OUT — SHARED JAVASCRIPT
   Handles: nav scroll, mobile menu, dark mode toggle
   All pages include this file.
═══════════════════════════════════════════════════ */

(function () {
  'use strict';

  /* ── DOM refs ── */
  const nav        = document.querySelector('.fo-nav');
  const hamburger  = document.querySelector('.nav-hamburger');
  const mobileMenu = document.querySelector('.nav-mobile-menu');

  /* ── NAV: add shadow on scroll ── */
  if (nav) {
    window.addEventListener('scroll', () => {
      nav.classList.toggle('scrolled', window.scrollY > 20);
    }, { passive: true });
  }

  /* ── MOBILE MENU toggle ── */
  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', () => {
      const open = mobileMenu.classList.toggle('open');
      hamburger.classList.toggle('open', open);
      document.body.style.overflow = open ? 'hidden' : '';
    });

    /* Close on link click */
    mobileMenu.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => {
        mobileMenu.classList.remove('open');
        hamburger.classList.remove('open');
        document.body.style.overflow = '';
      });
    });

    /* Close on outside click */
    document.addEventListener('click', e => {
      if (nav && mobileMenu && !nav.contains(e.target) && !mobileMenu.contains(e.target)) {
        mobileMenu.classList.remove('open');
        hamburger.classList.remove('open');
        document.body.style.overflow = '';
      }
    });
  }

  /* ── SMOOTH SCROLL for internal anchors ── */
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const id = a.getAttribute('href');
      if (id === '#') return;
      const target = document.querySelector(id);
      if (target) {
        e.preventDefault();
        const offset = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-h')) || 76;
        const top = target.getBoundingClientRect().top + window.scrollY - offset - 8;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });

  /* ── CONTACT FORM validation (contato page) ── */
  const form = document.getElementById('contact-form');
  if (form) {
    const rules = {
      nome:     { min:2, max:50, pattern:/^[a-zA-ZÀ-ÿ\s]+$/, label:'Nome' },
      sobrenome:{ min:2, max:50, pattern:/^[a-zA-ZÀ-ÿ\s]+$/, label:'Sobrenome', optional:true },
      email:    { email:true, label:'Email' },
      mensagem: { min:10, max:5000, label:'Mensagem' },
    };

    function validate(name, value) {
      const r = rules[name];
      if (!r) return null;
      const v = value.trim();
      if (!v) return r.optional ? null : `${r.label} é obrigatório.`;
      if (r.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) return 'Email inválido.';
      if (r.min && v.length < r.min) return `${r.label} deve ter no mínimo ${r.min} caracteres.`;
      if (r.max && v.length > r.max) return `${r.label} deve ter no máximo ${r.max} caracteres.`;
      if (r.pattern && !r.pattern.test(v)) return `${r.label} deve conter apenas letras e espaços.`;
      return null;
    }

    function showState(field, error) {
      field.classList.toggle('is-invalid', !!error);
      const fb = field.parentElement.querySelector('.invalid-feedback');
      if (fb) fb.textContent = error || '';
    }

    /* Real-time on blur */
    form.querySelectorAll('input,textarea').forEach(el => {
      el.addEventListener('blur', () => {
        if (el.value.trim() || !rules[el.name]?.optional) {
          showState(el, validate(el.name, el.value));
        }
      });
    });

    /* Submit */
    form.addEventListener('submit', e => {
      let ok = true;
      form.querySelectorAll('input,textarea').forEach(el => {
        const err = validate(el.name, el.value);
        showState(el, err);
        if (err) ok = false;
      });
      if (!ok) {
        e.preventDefault();
        form.querySelector('.is-invalid')?.scrollIntoView({ behavior:'smooth', block:'center' });
        return;
      }
      const btn = form.querySelector('[type="submit"]');
      btn.disabled = true;
      btn.textContent = 'Enviando...';
    });
  }

})();
