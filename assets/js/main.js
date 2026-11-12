/**
 * LOVE SHACK — LUXURY BEACHFRONT SANCTUARY (ARUBA)
 * Client-Side Interactive JavaScript Engine
 */

document.addEventListener("DOMContentLoaded", () => {
  // ------------------------------------------------------------------------
  // 1. Dynamic Copyright Year
  // ------------------------------------------------------------------------
  const yearEl = document.getElementById("year");
  if (yearEl) {
    yearEl.textContent = new Date().getFullYear();
  }

  // ------------------------------------------------------------------------
  // 2. Floating Navbar Scroll State & Mobile Menu
  // ------------------------------------------------------------------------
  const headerWrapper = document.querySelector(".header-wrapper");
  if (headerWrapper) {
    const handleScroll = () => {
      if (window.scrollY > 30) {
        headerWrapper.classList.add("scrolled");
      } else {
        headerWrapper.classList.remove("scrolled");
      }
    };
    window.addEventListener("scroll", handleScroll, { passive: true });
    handleScroll();
  }

  const menuBtn = document.querySelector(".menu-btn");
  const nav = document.querySelector(".nav");

  if (menuBtn && nav) {
    const toggleMenu = (open) => {
      const isOpen = typeof open === "boolean" ? open : !nav.classList.contains("open");
      nav.classList.toggle("open", isOpen);
      menuBtn.setAttribute("aria-expanded", String(isOpen));
      
      // Toggle Hamburger vs Close Icon
      if (isOpen) {
        menuBtn.innerHTML = `
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        `;
      } else {
        menuBtn.innerHTML = `
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        `;
      }
    };

    menuBtn.addEventListener("click", () => toggleMenu());

    // Close when clicking any nav link
    nav.querySelectorAll("a").forEach(link => {
      link.addEventListener("click", () => toggleMenu(false));
    });

    // Close on click outside
    document.addEventListener("click", (e) => {
      if (nav.classList.contains("open") && !nav.contains(e.target) && !menuBtn.contains(e.target)) {
        toggleMenu(false);
      }
    });

    // Close on Escape key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && nav.classList.contains("open")) {
        toggleMenu(false);
      }
    });
  }

  // ------------------------------------------------------------------------
  // 3. Scroll Reveal Animations (with prefers-reduced-motion support)
  // ------------------------------------------------------------------------
  const revealElements = document.querySelectorAll(
    ".reveal, .intro-visual, .intro-content, .experience-card, .gallery-item, .amenity-category-card, .review-card, .location-info, .map-visual-card, .room-card, .rule-box"
  );

  revealElements.forEach(el => el.classList.add("reveal"));

  if ("IntersectionObserver" in window && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: "0px 0px -40px 0px"
    });

    revealElements.forEach(el => observer.observe(el));
  } else {
    revealElements.forEach(el => el.classList.add("is-visible"));
  }

  // ------------------------------------------------------------------------
  // 4. Hero Search Bar (index.html) Date Management & Routing
  // ------------------------------------------------------------------------
  const heroSearch = document.getElementById("heroSearchForm");
  if (heroSearch) {
    const heroCi = document.getElementById("heroCheckin");
    const heroCo = document.getElementById("heroCheckout");
    const today = new Date();
    const localToday = new Date(today.getTime() - today.getTimezoneOffset() * 60000).toISOString().slice(0, 10);

    if (heroCi && heroCo) {
      heroCi.min = localToday;

      heroCi.addEventListener("change", () => {
        if (heroCi.value) {
          const nextDay = new Date(heroCi.value);
          nextDay.setDate(nextDay.getDate() + 3); // 3 nights minimum
          const minCheckout = nextDay.toISOString().slice(0, 10);
          heroCo.min = minCheckout;
          if (heroCo.value && heroCo.value < minCheckout) {
            heroCo.value = minCheckout;
          }
        }
      });
    }

    heroSearch.addEventListener("submit", (e) => {
      e.preventDefault();
      const checkinVal = heroCi ? heroCi.value : "";
      const checkoutVal = heroCo ? heroCo.value : "";
      const guestsEl = document.getElementById("heroGuests");
      const guestsVal = guestsEl ? guestsEl.value : "2";

      let targetUrl = "booking.html";
      const params = new URLSearchParams();
      if (checkinVal) params.append("checkin", checkinVal);
      if (checkoutVal) params.append("checkout", checkoutVal);
      if (guestsVal) params.append("guests", guestsVal);

      if (params.toString()) {
        targetUrl += "?" + params.toString();
      }
      window.location.href = targetUrl;
    });
  }

  // ------------------------------------------------------------------------
  // 5. Booking Page (booking.html) Live Calculation & Validation
  // ------------------------------------------------------------------------
  const bookingForm = document.getElementById("bookingForm");
  if (bookingForm) {
    const ciInput = document.getElementById("checkin");
    const coInput = document.getElementById("checkout");
    const adultsSelect = document.getElementById("adults");
    const childrenSelect = document.getElementById("children");
    const feedbackBox = document.getElementById("bookingFeedback");

    // Summary Elements
    const sumCheckin = document.getElementById("sumCheckin");
    const sumCheckout = document.getElementById("sumCheckout");
    const sumNights = document.getElementById("sumNights");
    const sumTotal = document.getElementById("sumTotal");
    const sumGuests = document.getElementById("sumGuests");

    const NIGHTLY_RATE = 650; // $650 USD / night luxury rate
    const MIN_NIGHTS = 3;

    const today = new Date();
    const localToday = new Date(today.getTime() - today.getTimezoneOffset() * 60000).toISOString().slice(0, 10);

    if (ciInput) ciInput.min = localToday;

    // Helper: format YYYY-MM-DD into luxury readable string e.g. "Oct 14, 2026"
    const formatDateDisplay = (dateStr) => {
      if (!dateStr) return "Not selected";
      const parts = dateStr.split("-");
      if (parts.length !== 3) return dateStr;
      const d = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
      return d.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });
    };

    const updateBookingSummary = () => {
      const ciVal = ciInput ? ciInput.value : "";
      const coVal = coInput ? coInput.value : "";
      const adultsVal = adultsSelect ? parseInt(adultsSelect.value, 10) || 2 : 2;
      const childrenVal = childrenSelect ? parseInt(childrenSelect.value, 10) || 0 : 0;
      const totalGuests = adultsVal + childrenVal;

      if (sumCheckin) sumCheckin.textContent = formatDateDisplay(ciVal);
      if (sumCheckout) sumCheckout.textContent = formatDateDisplay(coVal);
      if (sumGuests) sumGuests.textContent = `${totalGuests} Guest${totalGuests > 1 ? 's' : ''}`;

      if (ciVal && coVal) {
        const d1 = new Date(ciVal);
        const d2 = new Date(coVal);
        const diffTime = d2.getTime() - d1.getTime();
        const diffNights = Math.round(diffTime / (1000 * 60 * 60 * 24));

        if (diffNights > 0) {
          if (sumNights) sumNights.textContent = `${diffNights} Night${diffNights > 1 ? 's' : ''}`;
          const totalEstimated = diffNights * NIGHTLY_RATE;
          if (sumTotal) sumTotal.textContent = `$${totalEstimated.toLocaleString()} USD (est.)`;
        } else {
          if (sumNights) sumNights.textContent = "—";
          if (sumTotal) sumTotal.textContent = "—";
        }
      } else {
        if (sumNights) sumNights.textContent = "—";
        if (sumTotal) sumTotal.textContent = "From $650 / night";
      }
    };

    // Parse URL Search Parameters if forwarded from Hero Search
    const urlParams = new URLSearchParams(window.location.search);
    const paramCi = urlParams.get("checkin");
    const paramCo = urlParams.get("checkout");
    const paramGuests = urlParams.get("guests");

    if (paramCi && ciInput) {
      ciInput.value = paramCi;
    }
    if (paramCo && coInput) {
      coInput.value = paramCo;
    }
    if (paramGuests && adultsSelect) {
      const gNum = parseInt(paramGuests, 10);
      if (gNum >= 1 && gNum <= 6) {
        adultsSelect.value = String(gNum);
      }
    }

    if (ciInput) {
      ciInput.addEventListener("change", () => {
        if (ciInput.value) {
          const nextDay = new Date(ciInput.value);
          nextDay.setDate(nextDay.getDate() + MIN_NIGHTS);
          const minCheckout = nextDay.toISOString().slice(0, 10);
          coInput.min = minCheckout;
          if (coInput.value && coInput.value < minCheckout) {
            coInput.value = minCheckout;
          }
        }
        updateBookingSummary();
      });
    }

    if (coInput) coInput.addEventListener("change", updateBookingSummary);
    if (adultsSelect) adultsSelect.addEventListener("change", updateBookingSummary);
    if (childrenSelect) childrenSelect.addEventListener("change", updateBookingSummary);

    // Initial calculation on page load
    updateBookingSummary();

    // Form Submission Handling
    bookingForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const ciVal = ciInput.value;
      const coVal = coInput.value;

      if (!ciVal || !coVal) {
        showFeedback(feedbackBox, "Please select both check-in and check-out dates.", "error");
        return;
      }

      const d1 = new Date(ciVal);
      const d2 = new Date(coVal);
      const diffTime = d2.getTime() - d1.getTime();
      const diffNights = Math.round(diffTime / (1000 * 60 * 60 * 24));

      if (diffNights <= 0) {
        showFeedback(feedbackBox, "Please select a check-out date that comes after your check-in date.", "error");
        return;
      }

      if (diffNights < MIN_NIGHTS) {
        showFeedback(feedbackBox, `Love Shack has a ${MIN_NIGHTS}-night minimum stay requirement. Please select ${MIN_NIGHTS} or more nights.`, "error");
        return;
      }

      const nameVal = document.getElementById("bookingName") ? document.getElementById("bookingName").value : "Guest";
      showFeedback(
        feedbackBox,
        `Thank you, ${nameVal}! Your booking enquiry for ${diffNights} nights has been submitted. Our island host will review date availability and respond to you within 24 hours with exact pricing. (Frontend preview)`,
        "success"
      );

      bookingForm.reset();
      if (ciInput) ciInput.min = localToday;
      updateBookingSummary();
    });
  }

  // ------------------------------------------------------------------------
  // 6. Contact Page (contact.html) Form & FAQ Accordion
  // ------------------------------------------------------------------------
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    const contactFeedback = document.getElementById("contactFeedback");
    contactForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const nameVal = document.getElementById("contactName") ? document.getElementById("contactName").value : "Guest";
      showFeedback(
        contactFeedback,
        `Thank you, ${nameVal}! Your message has been received. Our concierge team in Aruba will reply shortly. (Frontend preview)`,
        "success"
      );
      contactForm.reset();
    });
  }

  // FAQ Accordion
  const faqItems = document.querySelectorAll(".faq-item");
  faqItems.forEach(item => {
    const questionBtn = item.querySelector(".faq-question");
    if (questionBtn) {
      questionBtn.addEventListener("click", () => {
        const isActive = item.classList.contains("active");
        // Close other items for neat accordion behavior
        faqItems.forEach(other => {
          if (other !== item) other.classList.remove("active");
        });
        item.classList.toggle("active", !isActive);
      });
    }
  });

  // ------------------------------------------------------------------------
  // 7. Lightbox Gallery Viewer
  // ------------------------------------------------------------------------
  const lightboxModal = document.getElementById("lightboxModal");
  if (lightboxModal) {
    const lightboxImg = lightboxModal.querySelector(".lightbox-img");
    const lightboxTitle = lightboxModal.querySelector(".lightbox-title");
    const lightboxClose = lightboxModal.querySelector(".lightbox-close");

    const openLightbox = (imgSrc, title) => {
      if (lightboxImg) lightboxImg.src = imgSrc;
      if (lightboxTitle) lightboxTitle.textContent = title || "Love Shack Villa · Savaneta, Aruba";
      lightboxModal.classList.add("active");
      document.body.style.overflow = "hidden";
    };

    const closeLightbox = () => {
      lightboxModal.classList.remove("active");
      document.body.style.overflow = "";
    };

    document.querySelectorAll(".gallery-item, [data-lightbox]").forEach(item => {
      item.addEventListener("click", () => {
        const img = item.querySelector("img");
        if (img) {
          const caption = item.getAttribute("data-caption") || (item.querySelector(".gallery-caption") ? item.querySelector(".gallery-caption").textContent.trim() : img.alt);
          openLightbox(img.src, caption);
        }
      });
    });

    if (lightboxClose) lightboxClose.addEventListener("click", closeLightbox);

    lightboxModal.addEventListener("click", (e) => {
      if (e.target === lightboxModal || e.target.classList.contains("lightbox-content")) {
        closeLightbox();
      }
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && lightboxModal.classList.contains("active")) {
        closeLightbox();
      }
    });
  }

  // ------------------------------------------------------------------------
  // 8. Amenities Modal (index.html)
  // ------------------------------------------------------------------------
  const amenitiesModal = document.getElementById("amenitiesModal");
  const openAmenitiesBtn = document.getElementById("openAmenitiesModalBtn");
  const closeAmenitiesBtn = document.getElementById("closeAmenitiesModalBtn");
  const amenitiesBackdrop = document.getElementById("amenitiesModalBackdrop");

  if (amenitiesModal && openAmenitiesBtn) {
    const openModal = () => {
      amenitiesModal.classList.add("active");
      amenitiesModal.setAttribute("aria-hidden", "false");
      openAmenitiesBtn.setAttribute("aria-expanded", "true");
      document.body.style.overflow = "hidden";
      if (closeAmenitiesBtn) closeAmenitiesBtn.focus();
    };

    const closeModal = () => {
      amenitiesModal.classList.remove("active");
      amenitiesModal.setAttribute("aria-hidden", "true");
      openAmenitiesBtn.setAttribute("aria-expanded", "false");
      document.body.style.overflow = "";
      openAmenitiesBtn.focus();
    };

    openAmenitiesBtn.addEventListener("click", openModal);
    if (closeAmenitiesBtn) closeAmenitiesBtn.addEventListener("click", closeModal);
    if (amenitiesBackdrop) amenitiesBackdrop.addEventListener("click", closeModal);

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && amenitiesModal.classList.contains("active")) {
        closeModal();
      }
    });
  }

  // ------------------------------------------------------------------------
  // 9. Show More Reviews Toggle (index.html)
  // ------------------------------------------------------------------------
  const toggleReviewsBtn = document.getElementById("toggleReviewsBtn");
  const reviewsMoreWrap = document.getElementById("reviewsMoreWrap");

  if (toggleReviewsBtn && reviewsMoreWrap) {
    toggleReviewsBtn.addEventListener("click", () => {
      const isExpanded = reviewsMoreWrap.classList.contains("expanded");
      if (isExpanded) {
        reviewsMoreWrap.classList.remove("expanded");
        reviewsMoreWrap.setAttribute("aria-hidden", "true");
        toggleReviewsBtn.setAttribute("aria-expanded", "false");
        toggleReviewsBtn.classList.remove("active");
        const btnText = toggleReviewsBtn.querySelector(".btn-text");
        if (btnText) btnText.textContent = "Show More Reviews (12 Total)";

        const reviewsSec = document.getElementById("reviews");
        if (reviewsSec) {
          reviewsSec.scrollIntoView({ behavior: "smooth", block: "start" });
        }
      } else {
        reviewsMoreWrap.classList.add("expanded");
        reviewsMoreWrap.setAttribute("aria-hidden", "false");
        toggleReviewsBtn.setAttribute("aria-expanded", "true");
        toggleReviewsBtn.classList.add("active");
        const btnText = toggleReviewsBtn.querySelector(".btn-text");
        if (btnText) btnText.textContent = "Show Fewer Reviews";

        reviewsMoreWrap.querySelectorAll(".review-card").forEach(card => {
          card.classList.add("is-visible");
        });
      }
    });
  }

  // Helper for feedback alerts
  function showFeedback(el, msg, type) {
    if (!el) return;
    el.textContent = msg;
    el.className = `form-feedback ${type}`;
    el.scrollIntoView({ behavior: "smooth", block: "nearest" });
  }
});
