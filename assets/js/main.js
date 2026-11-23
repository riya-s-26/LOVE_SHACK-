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
    // Ensure both hamburger and close icons exist inside menuBtn
    let hamburgerIcon = menuBtn.querySelector(".icon-hamburger");
    let closeIcon = menuBtn.querySelector(".icon-close");

    if (!hamburgerIcon || !closeIcon) {
      menuBtn.innerHTML = `
        <svg class="icon-hamburger" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
        <svg class="icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      `;
      hamburgerIcon = menuBtn.querySelector(".icon-hamburger");
      closeIcon = menuBtn.querySelector(".icon-close");
    }

    const toggleMenu = (open) => {
      const isOpen = typeof open === "boolean" ? open : !nav.classList.contains("open");
      nav.classList.toggle("open", isOpen);
      menuBtn.classList.toggle("active", isOpen);
      menuBtn.setAttribute("aria-expanded", String(isOpen));
      menuBtn.setAttribute("aria-label", isOpen ? "Close navigation menu" : "Open navigation menu");

      // Synchronize icon display cleanly without DOM replacement
      if (hamburgerIcon) hamburgerIcon.style.display = isOpen ? "none" : "block";
      if (closeIcon) closeIcon.style.display = isOpen ? "block" : "none";
    };

    // Toggle menu on button click
    menuBtn.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      toggleMenu();
    });

    // Close when clicking any nav link
    nav.querySelectorAll("a").forEach(link => {
      link.addEventListener("click", () => {
        toggleMenu(false);
      });
    });

    // Close on click outside
    document.addEventListener("click", (e) => {
      if (!nav.classList.contains("open")) return;
      if (nav.contains(e.target) || menuBtn.contains(e.target) || (e.target.closest && (e.target.closest(".menu-btn") || e.target.closest(".nav")))) {
        return;
      }
      toggleMenu(false);
    });

    // Close on Escape key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && nav.classList.contains("open")) {
        toggleMenu(false);
      }
    });

    // Close on resize to desktop layout
    window.addEventListener("resize", () => {
      if (window.innerWidth > 991 && nav.classList.contains("open")) {
        toggleMenu(false);
      }
    }, { passive: true });
  }

  // ------------------------------------------------------------------------
  // 3. Scroll Reveal Animations (with prefers-reduced-motion support)
  // ------------------------------------------------------------------------
  const revealElements = Array.from(document.querySelectorAll(
    ".reveal, .intro-section .intro-visual, .intro-section .intro-content, .experience-card, .gallery-item, .amenity-category-card, .review-card, .location-info, .map-visual-card, .room-card, .rule-box"
  ));

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

      const actionAttr = heroSearch.getAttribute("action");
      let targetUrl = (actionAttr && actionAttr.trim() !== "") ? actionAttr : (window.location.pathname.endsWith(".html") ? "booking.html" : "booking.php");
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
  // 5. Booking Workflow (booking.html / booking.php) — Complete 8-Step Engine
  // ------------------------------------------------------------------------
  const paneStep1 = document.getElementById("paneStep1");
  if (paneStep1) {
    const ciInput = document.getElementById("checkin");
    const coInput = document.getElementById("checkout");
    const adultsSelect = document.getElementById("adults");
    const childrenSelect = document.getElementById("children");
    const feedbackStep1 = document.getElementById("feedbackStep1");
    const feedbackStep4 = document.getElementById("feedbackStep4");
    const feedbackStep5 = document.getElementById("feedbackStep5");

    // Right-hand live summary elements
    const sumCheckin = document.getElementById("sumCheckin");
    const sumCheckout = document.getElementById("sumCheckout");
    const sumNights = document.getElementById("sumNights");
    const sumTotal = document.getElementById("sumTotal");
    const sumGuests = document.getElementById("sumGuests");
    const sumPricingSubcaption = document.getElementById("sumPricingSubcaption");

    // State Tracking
    const state = {
      checkin: "",
      checkout: "",
      adults: 2,
      children: 0,
      nights: 0,
      nightlyRate: 650,
      accommodationTotal: 0,
      cleaningFee: 180,
      services: [], // array of { id, name, price, qty }
      servicesTotal: 0,
      estimatedTotal: 0,
      guest: {
        firstName: "",
        lastName: "",
        email: "",
        phone: "",
        country: "",
        notes: ""
      }
    };

    const MIN_NIGHTS = 3;
    const today = new Date();
    const localToday = new Date(today.getTime() - today.getTimezoneOffset() * 60000).toISOString().slice(0, 10);

    if (ciInput) ciInput.min = localToday;

    const formatDateDisplay = (dateStr) => {
      if (!dateStr) return "Not selected";
      const parts = dateStr.split("-");
      if (parts.length !== 3) return dateStr;
      const d = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
      return d.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" });
    };

    // Step Transition Controller
    const goToStep = (stepNum) => {
      let targetPane = document.getElementById(typeof stepNum === 'string' && stepNum.startsWith('pane') ? stepNum : `paneStep${stepNum}`);
      if (!targetPane && (stepNum === '3b' || stepNum === 3.5)) {
        targetPane = document.getElementById('paneStep3');
      }
      if (!targetPane) return;

      document.querySelectorAll(".booking-step-pane").forEach(pane => pane.classList.remove("active"));
      targetPane.classList.add("active");

      // Determine numeric step for 1-5 progress bar indicators: Step 3b is part of Step 3
      const numericStep = (stepNum === '3b' || stepNum === 3.5) ? 3 : (typeof stepNum === 'number' ? stepNum : parseInt(stepNum, 10) || 1);

      // Update Step Indicators
      for (let i = 1; i <= 5; i++) {
        const ind = document.getElementById(`stepIndicator${i}`);
        if (!ind) continue;
        ind.classList.remove("active", "completed");
        if (i < numericStep) {
          ind.classList.add("completed");
        } else if (i === numericStep) {
          ind.classList.add("active");
        }
      }

      const formCard = document.querySelector(".form-card");
      if (formCard) formCard.scrollIntoView({ behavior: "smooth", block: "start" });
    };

    // Live Right-side Summary Card Updater
    const updateLiveSummaryCard = () => {
      const ciVal = ciInput ? ciInput.value : state.checkin;
      const coVal = coInput ? coInput.value : state.checkout;
      const adultsVal = adultsSelect ? parseInt(adultsSelect.value, 10) || 2 : state.adults;
      const childrenVal = childrenSelect ? parseInt(childrenSelect.value, 10) || 0 : state.children;
      const totalGuests = adultsVal + childrenVal;

      if (sumCheckin) sumCheckin.textContent = formatDateDisplay(ciVal);
      if (sumCheckout) sumCheckout.textContent = formatDateDisplay(coVal);
      if (sumGuests) sumGuests.textContent = `${totalGuests} Guest${totalGuests > 1 ? 's' : ''}`;

      if (ciVal && coVal) {
        const d1 = new Date(ciVal);
        const d2 = new Date(coVal);
        const diffNights = Math.round((d2.getTime() - d1.getTime()) / (1000 * 60 * 60 * 24));

        if (diffNights > 0) {
          state.nights = diffNights;
          if (sumNights) sumNights.textContent = `${diffNights} Night${diffNights > 1 ? 's' : ''}`;
          
          state.accommodationTotal = diffNights * state.nightlyRate;
          state.servicesTotal = 0;
          state.estimatedTotal = state.accommodationTotal + state.cleaningFee;

          if (sumTotal) sumTotal.textContent = `$${state.estimatedTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} USD`;
          if (sumPricingSubcaption) {
            sumPricingSubcaption.textContent = `${state.nightlyRate}/nt × ${diffNights} nts + ${state.cleaningFee} cleaning${state.services.length > 0 ? ` (${state.services.length} concierge enquir${state.services.length > 1 ? 'ies' : 'y'})` : ''}`;
          }
        } else {
          if (sumNights) sumNights.textContent = "—";
          if (sumTotal) sumTotal.textContent = "—";
        }
      } else {
        if (sumNights) sumNights.textContent = "—";
        if (sumTotal) sumTotal.textContent = `From $${state.nightlyRate} / night`;
        if (sumPricingSubcaption) sumPricingSubcaption.textContent = `Standard luxury rate · 3-night min`;
      }
    };

    // Pre-fill from URL parameters (if visitor clicked hero search on homepage)
    const urlParams = new URLSearchParams(window.location.search);
    const paramCi = urlParams.get("checkin");
    const paramCo = urlParams.get("checkout");
    const paramGuests = urlParams.get("guests");

    if (paramCi && ciInput) ciInput.value = paramCi;
    if (paramCo && coInput) coInput.value = paramCo;
    if (paramGuests && adultsSelect) {
      const gNum = parseInt(paramGuests, 10);
      if (gNum >= 1 && gNum <= 6) adultsSelect.value = String(gNum);
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
        updateLiveSummaryCard();
      });
    }

    if (coInput) coInput.addEventListener("change", updateLiveSummaryCard);
    if (adultsSelect) adultsSelect.addEventListener("change", updateLiveSummaryCard);
    if (childrenSelect) childrenSelect.addEventListener("change", updateLiveSummaryCard);

    updateLiveSummaryCard();

    // ------------------------------------------------------------------------
    // STEP 1 -> STEP 2: Check Availability & Rates
    // ------------------------------------------------------------------------
    const btnCheckAvail = document.getElementById("btnCheckAvailability");
    if (btnCheckAvail) {
      btnCheckAvail.addEventListener("click", async () => {
        const ciVal = ciInput.value;
        const coVal = coInput.value;

        if (!ciVal || !coVal) {
          showFeedback(feedbackStep1, "Please choose both your arrival and departure dates.", "error");
          return;
        }

        const d1 = new Date(ciVal);
        const d2 = new Date(coVal);
        const diffNights = Math.round((d2.getTime() - d1.getTime()) / (1000 * 60 * 60 * 24));

        if (diffNights <= 0) {
          showFeedback(feedbackStep1, "Check-out date must be strictly after your check-in date.", "error");
          return;
        }

        if (diffNights < MIN_NIGHTS) {
          showFeedback(feedbackStep1, `The Love Shack has a ${MIN_NIGHTS}-night minimum stay policy. Please select ${MIN_NIGHTS} or more nights.`, "error");
          return;
        }

        btnCheckAvail.disabled = true;
        btnCheckAvail.innerHTML = `Checking Calendar... <svg class="spinning" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle><path d="M12 2a10 10 0 0 1 10 10"></path></svg>`;

        // Populate calculated booking state
        state.checkin = ciVal;
        state.checkout = coVal;
        state.adults = parseInt(adultsSelect.value, 10) || 2;
        state.children = parseInt(childrenSelect.value, 10) || 0;
        state.nights = diffNights;
        state.accommodationTotal = state.nights * state.nightlyRate;
        state.estimatedTotal = state.accommodationTotal + state.cleaningFee;

        // Populate Step 2 Fields
        const rateSumCi = document.getElementById("rateSummaryCheckin");
        const rateSumCo = document.getElementById("rateSummaryCheckout");
        const rateSumNights = document.getElementById("rateSummaryNights");
        const rateSumParty = document.getElementById("rateSummaryParty");
        const rateSumRate = document.getElementById("rateSummaryRate");
        const rateSumSub = document.getElementById("rateSummarySubtotal");
        const rateSumCleaning = document.getElementById("rateSummaryCleaning");
        const rateSumGrand = document.getElementById("rateSummaryGrandTotal");

        if (rateSumCi) rateSumCi.textContent = formatDateDisplay(state.checkin);
        if (rateSumCo) rateSumCo.textContent = formatDateDisplay(state.checkout);
        if (rateSumNights) rateSumNights.textContent = `${state.nights} Night${state.nights > 1 ? 's' : ''}`;
        if (rateSumParty) rateSumParty.textContent = `${state.adults + state.children} Guests (${state.adults} Adults${state.children > 0 ? `, ${state.children} Children` : ''})`;
        if (rateSumRate) rateSumRate.textContent = `$${state.nightlyRate.toFixed(2)} USD / night`;
        if (rateSumSub) rateSumSub.textContent = `$${state.accommodationTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} USD`;
        if (rateSumCleaning) rateSumCleaning.textContent = `$${state.cleaningFee.toFixed(2)} USD`;
        if (rateSumGrand) rateSumGrand.textContent = `$${state.estimatedTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} USD`;

        updateLiveSummaryCard();
        goToStep(2);
        btnCheckAvail.disabled = false;
        btnCheckAvail.innerHTML = `Check Availability & Rates <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>`;
      });
    }

    // Step 2 buttons: BACK -> NEXT
    const btnBackToStep1 = document.getElementById("btnBackToStep1");
    if (btnBackToStep1) btnBackToStep1.addEventListener("click", () => goToStep(1));

    const btnGoToStep3 = document.getElementById("btnGoToStep3");
    if (btnGoToStep3) btnGoToStep3.addEventListener("click", () => goToStep(3));

    // ------------------------------------------------------------------------
    // STEP 3: Customise Your Stay (Extras Selection)
    // ------------------------------------------------------------------------
    const serviceCards = document.querySelectorAll(".service-addon-card, .service-addon-featured");
    const recalculateExtras = () => {
      state.services = [];

      const currentServiceCards = document.querySelectorAll(".service-addon-card, .service-addon-featured");
      currentServiceCards.forEach(card => {
        const checkbox = card.querySelector(".service-checkbox");
        if (checkbox && checkbox.checked) {
          card.classList.add("selected");
          const sId = card.getAttribute("data-service-id") || "";
          const sName = card.getAttribute("data-service-name") || "Custom Service";
          const sDisplay = card.getAttribute("data-service-display") || "Optional Enquiry";
          // Strictly enquiry-only: price is always 0 in calculations
          state.services.push({ id: sId, name: sName, price: 0, display: sDisplay });
        } else {
          card.classList.remove("selected");
        }
      });

      state.servicesTotal = 0; // Strictly enquiry only - zero charge added to total
      state.estimatedTotal = state.accommodationTotal + state.cleaningFee;
      updateLiveSummaryCard();
    };

    serviceCards.forEach(card => {
      card.addEventListener("click", (e) => {
        // If clicking directly on checkbox, let it change naturally
        if (e.target.tagName.toLowerCase() !== "input") {
          const cb = card.querySelector(".service-checkbox");
          if (cb) cb.checked = !cb.checked;
        }
        recalculateExtras();
      });
    });

    // Step 3 Navigation: BACK -> NEXT
    const btnBackToStep2 = document.getElementById("btnBackToStep2");
    if (btnBackToStep2) btnBackToStep2.addEventListener("click", () => goToStep(2));

    const btnGoToStep4 = document.getElementById("btnGoToStep4");
    if (btnGoToStep4) btnGoToStep4.addEventListener("click", () => goToStep(4));

    // ------------------------------------------------------------------------
    // STEP 4: Guest Details Validation
    // ------------------------------------------------------------------------
    const btnBackFromStep4 = document.getElementById("btnBackFromStep4");
    if (btnBackFromStep4) btnBackFromStep4.addEventListener("click", () => goToStep(3));

    const btnGoToStep5 = document.getElementById("btnGoToStep5");
    if (btnGoToStep5) {
      btnGoToStep5.addEventListener("click", () => {
        const fn = document.getElementById("firstName") ? document.getElementById("firstName").value.trim() : "";
        const ln = document.getElementById("lastName") ? document.getElementById("lastName").value.trim() : "";
        const em = document.getElementById("guestEmail") ? document.getElementById("guestEmail").value.trim() : "";
        const ph = document.getElementById("guestPhone") ? document.getElementById("guestPhone").value.trim() : "";
        const co = document.getElementById("guestCountry") ? document.getElementById("guestCountry").value.trim() : "";
        const sp = document.getElementById("specialRequests") ? document.getElementById("specialRequests").value.trim() : "";

        if (!fn || !ln) {
          showFeedback(feedbackStep4, "Please enter both your first and last name.", "error");
          return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!em || !emailRegex.test(em)) {
          showFeedback(feedbackStep4, "Please enter a valid email address.", "error");
          return;
        }

        if (!ph) {
          showFeedback(feedbackStep4, "Please provide your phone or WhatsApp number for concierge arrival coordination.", "error");
          return;
        }

        state.guest.firstName = fn;
        state.guest.lastName = ln;
        state.guest.email = em;
        state.guest.phone = ph;
        state.guest.country = co;
        state.guest.notes = sp;

        // Populate Step 5 Review
        const revDates = document.getElementById("revDates");
        const revDur = document.getElementById("revDuration");
        const revGuests = document.getElementById("revGuests");
        const revAcc = document.getElementById("revAccommodation");
        const revClean = document.getElementById("revCleaning");
        const revGrand = document.getElementById("revGrandTotal");
        const revName = document.getElementById("revGuestName");
        const revEm = document.getElementById("revGuestEmail");
        const revPh = document.getElementById("revGuestPhone");
        const revCo = document.getElementById("revGuestCountry");
        const revCoRow = document.getElementById("revGuestCountryRow");
        const revSp = document.getElementById("revSpecialRequests");
        const revSpRow = document.getElementById("revNotesRow");
        const revSvcBox = document.getElementById("revServicesBox");
        const revSvcList = document.getElementById("revServicesList");

        if (revDates) revDates.textContent = `${formatDateDisplay(state.checkin)} → ${formatDateDisplay(state.checkout)}`;
        if (revDur) revDur.textContent = `${state.nights} Night${state.nights > 1 ? 's' : ''}`;
        if (revGuests) revGuests.textContent = `${state.adults + state.children} Guests (${state.adults} Adults${state.children > 0 ? `, ${state.children} Children` : ''})`;
        if (revAcc) revAcc.textContent = `$${state.accommodationTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} USD`;
        if (revClean) revClean.textContent = `$${state.cleaningFee.toFixed(2)} USD`;
        if (revGrand) revGrand.textContent = `$${state.estimatedTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} USD`;

        if (revName) revName.textContent = `${fn} ${ln}`;
        if (revEm) revEm.textContent = em;
        if (revPh) revPh.textContent = ph;

        if (co) {
          if (revCo) revCo.textContent = co;
          if (revCoRow) revCoRow.style.display = "flex";
        } else if (revCoRow) {
          revCoRow.style.display = "none";
        }

        if (sp) {
          if (revSp) revSp.textContent = `“${sp}”`;
          if (revSpRow) revSpRow.style.display = "flex";
        } else if (revSpRow) {
          revSpRow.style.display = "none";
        }

        // Extras in review
        if (state.services.length > 0 && revSvcBox && revSvcList) {
          revSvcBox.style.display = "block";
          revSvcList.innerHTML = state.services.map(s => `
            <div class="review-row">
              <span>${s.name}</span>
              <strong>${s.display ? s.display : (s.price > 0 ? `+$${s.price.toFixed(2)}` : 'Price on request')}</strong>
            </div>
          `).join("");
        } else if (revSvcBox) {
          revSvcBox.style.display = "none";
        }

        goToStep(5);
      });
    }

    // Step 5 Edit Buttons
    const editDatesBtn = document.getElementById("editStepDatesBtn");
    if (editDatesBtn) editDatesBtn.addEventListener("click", () => goToStep(1));

    const editSvcBtn = document.getElementById("editStepServicesBtn");
    if (editSvcBtn) editSvcBtn.addEventListener("click", () => goToStep(3));

    const editGuestBtn = document.getElementById("editStepGuestBtn");
    if (editGuestBtn) editGuestBtn.addEventListener("click", () => goToStep(4));

    const btnBackToStep4 = document.getElementById("btnBackToStep4");
    if (btnBackToStep4) btnBackToStep4.addEventListener("click", () => goToStep(4));

    // ------------------------------------------------------------------------
    // STEP 5: Reservation Request Submission
    // ------------------------------------------------------------------------
    const btnSubmit = document.getElementById("btnSubmitBookingRequest");
    if (btnSubmit) {
      btnSubmit.addEventListener("click", () => {
        const feedbackStep5 = document.getElementById("feedbackStep5");
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = `Processing Request... <svg class="spinning" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle><path d="M12 2a10 10 0 0 1 10 10"></path></svg>`;

        setTimeout(() => {
          const guestName = (state.guest && state.guest.firstName) ? state.guest.firstName : "Guest";
          showFeedback(
            feedbackStep5,
            `Thank you, ${guestName}. Direct reservation processing will be activated upon live launch. To secure these dates immediately, please contact our host concierge directly at stay@theloveshackaruba.com or call +297 586 0000.`,
            "info"
          );
          btnSubmit.disabled = false;
          btnSubmit.innerHTML = `Submit Reservation Request <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
        }, 400);
      });
    }

    const btnBookAnother = document.getElementById("btnBookAnother");
    if (btnBookAnother) {
      btnBookAnother.addEventListener("click", () => {
        if (ciInput) ciInput.value = "";
        if (coInput) coInput.value = "";
        serviceCards.forEach(c => {
          const cb = c.querySelector(".service-checkbox");
          if (cb) cb.checked = false;
        });
        recalculateExtras();
        goToStep(1);
      });
    }
  }

  // ------------------------------------------------------------------------
  // 6. Direct Contact Inquiry Form
  // ------------------------------------------------------------------------
  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    const contactFeedback = document.getElementById("contactFeedback");
    contactForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const submitBtn = contactForm.querySelector('button[type="submit"]');
      const nameVal = document.getElementById("contactName") ? document.getElementById("contactName").value.trim() : "";
      const emailVal = document.getElementById("contactEmail") ? document.getElementById("contactEmail").value.trim() : "";
      const phoneVal = document.getElementById("contactPhone") ? document.getElementById("contactPhone").value.trim() : "";
      const subjectVal = document.getElementById("contactSubject") ? document.getElementById("contactSubject").value : "General Question";
      const messageVal = document.getElementById("contactMessage") ? document.getElementById("contactMessage").value.trim() : "";

      // Required field validation
      if (!nameVal || !emailVal || !messageVal) {
        showFeedback(contactFeedback, "Please fill in all required fields (Name, Email, Message).", "error");
        return;
      }

      // Email format validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(emailVal)) {
        showFeedback(contactFeedback, "Please provide a valid email address.", "error");
        return;
      }

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `Sending Inquiry... <svg class="spinning" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle><path d="M12 2a10 10 0 0 1 10 10"></path></svg>`;
      }

      setTimeout(() => {
        showFeedback(
          contactFeedback,
          `Thank you, ${nameVal}. Direct messaging will be enabled with our live release. For immediate assistance with your inquiry, please email stay@theloveshackaruba.com or call +297 586 0000.`,
          "info"
        );
        contactForm.reset();

        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = `Send Inquiry <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>`;
        }
      }, 350);
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

    document.querySelectorAll(".gallery-item, .mosaic-primary, .mosaic-card, [data-lightbox]").forEach(item => {
      item.addEventListener("click", () => {
        const img = item.querySelector("img");
        if (img) {
          const caption = item.getAttribute("data-caption") || 
            (item.querySelector(".mosaic-caption") ? item.querySelector(".mosaic-caption").textContent.trim() : 
            (item.querySelector(".gallery-caption") ? item.querySelector(".gallery-caption").textContent.trim() : img.alt));
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

  // ------------------------------------------------------------------------
  // 10. Interactive Bedroom Photo Carousels & Viewer with Autoplay
  // ------------------------------------------------------------------------
  const bedroomCarousels = document.querySelectorAll(".bedroom-carousel");
  const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  bedroomCarousels.forEach((carousel, carouselIdx) => {
    const slides = carousel.querySelectorAll(".bedroom-slide");
    const dots = carousel.querySelectorAll(".bedroom-carousel-dots .dot");
    const prevBtn = carousel.querySelector(".bedroom-carousel-nav.prev");
    const nextBtn = carousel.querySelector(".bedroom-carousel-nav.next");
    const photosBtn = carousel.querySelector(".bedroom-photos-btn");
    let currentIndex = 0;
    const total = slides.length;
    let autoplayTimer = null;
    let isHoveredOrFocused = false;
    let isInViewport = true;
    const AUTOPLAY_INTERVAL = 3500; // 3.5 seconds

    if (total <= 1) return;

    const showSlide = (idx) => {
      currentIndex = (idx + total) % total;
      slides.forEach((slide, i) => {
        slide.classList.toggle("active", i === currentIndex);
      });
      dots.forEach((dot, i) => {
        dot.classList.toggle("active", i === currentIndex);
      });
    };

    const nextSlide = () => {
      showSlide(currentIndex + 1);
    };

    const prevSlide = () => {
      showSlide(currentIndex - 1);
    };

    // Autoplay Controller
    const startAutoplay = () => {
      if (prefersReducedMotion || isHoveredOrFocused || !isInViewport) return;
      stopAutoplay();
      autoplayTimer = setInterval(nextSlide, AUTOPLAY_INTERVAL);
    };

    const stopAutoplay = () => {
      if (autoplayTimer) {
        clearInterval(autoplayTimer);
        autoplayTimer = null;
      }
    };

    const resetAutoplay = () => {
      stopAutoplay();
      startAutoplay();
    };

    // Staggered initial autoplay start so cards don't all flip in robotic sync
    if (!prefersReducedMotion) {
      setTimeout(() => {
        startAutoplay();
      }, 500 + carouselIdx * 700);
    }

    // Pause on hover
    carousel.addEventListener("mouseenter", () => {
      isHoveredOrFocused = true;
      stopAutoplay();
    });

    carousel.addEventListener("mouseleave", () => {
      isHoveredOrFocused = false;
      startAutoplay();
    });

    // Pause on focus within (accessibility)
    carousel.addEventListener("focusin", () => {
      isHoveredOrFocused = true;
      stopAutoplay();
    });

    carousel.addEventListener("focusout", (e) => {
      if (!carousel.contains(e.relatedTarget)) {
        isHoveredOrFocused = false;
        startAutoplay();
      }
    });

    // Pause when tab is hidden, resume when visible
    document.addEventListener("visibilitychange", () => {
      if (document.hidden) {
        stopAutoplay();
      } else {
        startAutoplay();
      }
    });

    // IntersectionObserver to pause when off-screen
    if ("IntersectionObserver" in window) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          isInViewport = entry.isIntersecting;
          if (entry.isIntersecting) {
            startAutoplay();
          } else {
            stopAutoplay();
          }
        });
      }, { threshold: 0.15 });
      observer.observe(carousel);
    }

    // Manual navigation controls
    if (prevBtn) {
      prevBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        prevSlide();
        resetAutoplay();
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        nextSlide();
        resetAutoplay();
      });
    }

    dots.forEach((dot, i) => {
      dot.addEventListener("click", (e) => {
        e.stopPropagation();
        showSlide(i);
        resetAutoplay();
      });
    });

    const triggerLightbox = (slideEl) => {
      const img = slideEl.querySelector("img");
      if (!img) return;
      const caption = img.alt || "The Love Shack Aruba";
      const lightboxModal = document.getElementById("lightboxModal");
      if (lightboxModal) {
        const lightboxImg = lightboxModal.querySelector(".lightbox-img");
        const lightboxTitle = lightboxModal.querySelector(".lightbox-title");
        if (lightboxImg) lightboxImg.src = img.src;
        if (lightboxTitle) lightboxTitle.textContent = caption;
        lightboxModal.classList.add("active");
        document.body.style.overflow = "hidden";
      }
    };

    // Clicking active slide opens lightbox
    slides.forEach((slide) => {
      slide.addEventListener("click", () => {
        triggerLightbox(slide);
      });
    });

    // Clicking "View Photos" button opens active photo in lightbox
    if (photosBtn) {
      photosBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        const activeSlide = carousel.querySelector(".bedroom-slide.active") || slides[0];
        if (activeSlide) triggerLightbox(activeSlide);
      });
    }

    // Touch swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    carousel.addEventListener("touchstart", (e) => {
      stopAutoplay();
      if (e.changedTouches && e.changedTouches[0]) {
        touchStartX = e.changedTouches[0].screenX;
      }
    }, { passive: true });

    carousel.addEventListener("touchend", (e) => {
      if (e.changedTouches && e.changedTouches[0]) {
        touchEndX = e.changedTouches[0].screenX;
        if (touchStartX - touchEndX > 40) {
          nextSlide();
        } else if (touchEndX - touchStartX > 40) {
          prevSlide();
        }
      }
      startAutoplay();
    }, { passive: true });
  });

  // ------------------------------------------------------------------------
  // 11. Sticky Header Offset Smooth Scrolling for Anchor Destinations
  // ------------------------------------------------------------------------
  const scrollToTargetWithOffset = (targetEl) => {
    if (!targetEl) return;
    const headerWrapper = document.querySelector(".header-wrapper");
    const headerHeight = headerWrapper ? headerWrapper.offsetHeight + 24 : 110;
    const elementPosition = targetEl.getBoundingClientRect().top;
    const offsetPosition = elementPosition + window.pageYOffset - headerHeight;
    window.scrollTo({
      top: offsetPosition,
      behavior: "smooth"
    });
  };

  // Handle direct hash navigation on page load
  if (window.location.hash) {
    const rawHash = window.location.hash;
    const cleanHash = (rawHash === "#rooms" || rawHash === "#accommodations") ? "#accommodations" : rawHash;
    const target = document.querySelector(cleanHash) || document.querySelector(rawHash);
    if (target) {
      setTimeout(() => {
        scrollToTargetWithOffset(target);
      }, 150);
    }
  }

  // Smooth scroll for in-page anchor clicks
  document.querySelectorAll('a[href*="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function(e) {
      const href = this.getAttribute("href");
      if (!href) return;
      const hashIndex = href.indexOf("#");
      if (hashIndex === -1) return;
      const hash = href.substring(hashIndex);
      if (!hash || hash === "#") return;

      const currentPath = window.location.pathname.split("/").pop() || "index.html";
      const targetPath = href.substring(0, hashIndex).split("/").pop();

      // Check if same page link
      if (!targetPath || targetPath === currentPath || (currentPath === "" && (targetPath === "index.html" || targetPath === "index.php"))) {
        const cleanTarget = (hash === "#rooms" || hash === "#accommodations") ? (document.querySelector("#accommodations") || document.querySelector("#rooms")) : document.querySelector(hash);
        if (cleanTarget) {
          e.preventDefault();
          scrollToTargetWithOffset(cleanTarget);
          if (history.pushState) {
            history.pushState(null, null, hash);
          }
        }
      }
    });
  });
});
