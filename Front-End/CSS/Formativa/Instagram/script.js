if (window.lucide && typeof window.lucide.createIcons === "function") {
  window.lucide.createIcons();
}

// =============================
// Theme System
// =============================
// Key used to persist user theme choice across reloads.
const THEME_STORAGE_KEY = "instagram-theme";

const updateThemeIcons = (theme) => {
  // Re-render lucide icons after replacing markup to avoid stale SVG nodes.
  const iconName = theme === "light" ? "sun" : "moon";
  const iconSelectors = [".icone-aparencia-menu", ".icone-aparencia-painel"];

  iconSelectors.forEach((selector) => {
    const currentIcon = document.querySelector(selector);
    if (!currentIcon) return;

    const className = currentIcon.getAttribute("class") || "";
    currentIcon.outerHTML = `<i data-lucide="${iconName}" class="${className}"></i>`;
  });

  if (window.lucide && typeof window.lucide.createIcons === "function") {
    window.lucide.createIcons();
  }
};

const applyTheme = (theme) => {
  // Single source of truth for all theme-side effects.
  const isLight = theme === "light";
  document.body.classList.toggle("tema-claro", isLight);
  updateThemeIcons(theme);

  const activeSwitch = document.getElementById("menuAparenciaSwitch");
  if (activeSwitch) {
    const isDark = !isLight;
    activeSwitch.classList.toggle("ativo", isDark);
    activeSwitch.setAttribute("aria-pressed", `${isDark}`);
  }

  try {
    window.localStorage.setItem(THEME_STORAGE_KEY, theme);
  } catch (error) {
    // Ignore storage failures (e.g. private mode restrictions).
  }
};

const loadInitialTheme = () => {
  let savedTheme = null;
  try {
    savedTheme = window.localStorage.getItem(THEME_STORAGE_KEY);
  } catch (error) {
    savedTheme = null;
  }

  const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
  const initialTheme =
    savedTheme === "light" || savedTheme === "dark"
      ? savedTheme
      : prefersDark
        ? "dark"
        : "light";

  applyTheme(initialTheme);
};

loadInitialTheme();

// =============================
// Sidebar Menu + Appearance Panel
// =============================
const menuToggleButton = document.querySelector(".item-menu-toggle");
const menuMaisPopup = document.getElementById("menuMaisPopup");
const sidebar = document.querySelector(".barra-lateral");
const menuAparenciaToggle = document.querySelector(".item-aparencia-toggle");
const menuAparenciaVoltar = document.querySelector(".menu-aparencia-voltar");
const menuAparenciaSwitch = document.getElementById("menuAparenciaSwitch");
const menuPainelPrincipal = document.querySelector(
  ".menu-mais-painel-principal",
);
const menuPainelAparencia = document.querySelector(
  ".menu-mais-painel-aparencia",
);

if (menuToggleButton && menuMaisPopup && sidebar) {
  const syncMenuPopupHeight = () => {
    // Keeps popup height in sync with the active panel to prevent clipping.
    const isAparenciaAberta =
      menuMaisPopup.classList.contains("aparencia-aberta");
    const painelAtivo = isAparenciaAberta
      ? menuPainelAparencia
      : menuPainelPrincipal;
    if (!painelAtivo) return;
    menuMaisPopup.style.height = `${painelAtivo.scrollHeight}px`;
  };

  const setAparenciaAberta = (isOpen) => {
    menuMaisPopup.classList.toggle("aparencia-aberta", isOpen);
    window.requestAnimationFrame(syncMenuPopupHeight);
  };

  const closeSidebarMenu = () => {
    setAparenciaAberta(false);
    menuMaisPopup.classList.remove("aberto");
    menuMaisPopup.setAttribute("aria-hidden", "true");
    menuToggleButton.setAttribute("aria-expanded", "false");
    sidebar.classList.remove("fixa-aberta");
  };

  const openSidebarMenu = () => {
    setAparenciaAberta(false);
    sidebar.classList.add("fixa-aberta");
    menuMaisPopup.classList.add("aberto");
    menuMaisPopup.setAttribute("aria-hidden", "false");
    menuToggleButton.setAttribute("aria-expanded", "true");
    syncMenuPopupHeight();
  };

  if (menuAparenciaToggle) {
    menuAparenciaToggle.addEventListener("click", () => {
      setAparenciaAberta(true);
    });
  }

  if (menuAparenciaVoltar) {
    menuAparenciaVoltar.addEventListener("click", () => {
      setAparenciaAberta(false);
    });
  }

  if (menuAparenciaSwitch) {
    menuAparenciaSwitch.addEventListener("click", () => {
      const isDarkModeOn = menuAparenciaSwitch.classList.contains("ativo");
      applyTheme(isDarkModeOn ? "light" : "dark");
    });
  }

  menuToggleButton.addEventListener("click", (event) => {
    event.stopPropagation();
    const isOpen = menuMaisPopup.classList.contains("aberto");
    if (isOpen) {
      closeSidebarMenu();
      return;
    }
    openSidebarMenu();
  });

  menuMaisPopup.addEventListener("click", (event) => {
    const option = event.target.closest(".menu-mais-opcao");
    if (!option) return;

    if (option.classList.contains("item-aparencia-toggle")) {
      return;
    }

    closeSidebarMenu();
  });

  document.addEventListener("click", (event) => {
    if (
      !sidebar.contains(event.target) &&
      !menuMaisPopup.contains(event.target) &&
      !menuToggleButton.contains(event.target)
    ) {
      closeSidebarMenu();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && menuMaisPopup.classList.contains("aberto")) {
      closeSidebarMenu();
    }
  });

  window.addEventListener("resize", () => {
    if (menuMaisPopup.classList.contains("aberto")) {
      syncMenuPopupHeight();
    }
  });
}

// =============================
// Stories Horizontal Navigation
// =============================
const storyTrack = document.querySelector(".storys-track");
const storyViewport = document.querySelector(".storys-viewport");
const storyPrevButton = document.querySelector(".story-prev");
const storyNextButton = document.querySelector(".story-next");

if (storyTrack && storyViewport && storyPrevButton && storyNextButton) {
  const storyCards = storyTrack.querySelectorAll(".story-card");
  const rootStyles = window.getComputedStyle(document.documentElement);
  const configuredVisible = parseInt(
    rootStyles.getPropertyValue("--stories-visible"),
    10,
  );
  const maxVisibleStories = Number.isNaN(configuredVisible)
    ? 6
    : Math.max(1, configuredVisible);
  let currentIndex = 0;
  let maxIndex = 0;

  const updateStories = () => {
    // Recompute visible cards based on runtime width (desktop/tablet/mobile).
    const firstCard = storyCards[0];
    if (!firstCard) return;

    const styles = window.getComputedStyle(storyTrack);
    const gap = parseFloat(styles.gap || styles.columnGap || "0");
    const cardWidth = firstCard.getBoundingClientRect().width;
    const availableWidth =
      storyViewport.parentElement?.clientWidth || storyViewport.clientWidth;

    const storiesVisible = Math.max(
      1,
      Math.min(
        maxVisibleStories,
        storyCards.length,
        Math.floor((availableWidth + gap) / (cardWidth + gap)),
      ),
    );

    const fittedViewportWidth =
      storiesVisible * cardWidth + (storiesVisible - 1) * gap;
    storyViewport.style.width = `${fittedViewportWidth}px`;

    maxIndex = Math.max(storyCards.length - storiesVisible, 0);
    if (currentIndex > maxIndex) {
      currentIndex = maxIndex;
    }

    const offset = currentIndex * (cardWidth + gap);

    storyTrack.style.transform = `translateX(-${offset}px)`;
    const hasPrevious = currentIndex > 0;
    const hasNext = currentIndex < maxIndex;

    storyPrevButton.style.display = hasPrevious ? "flex" : "none";
    storyNextButton.style.display = hasNext ? "flex" : "none";

    storyPrevButton.disabled = !hasPrevious;
    storyNextButton.disabled = !hasNext;
  };

  storyPrevButton.addEventListener("click", () => {
    if (currentIndex > 0) {
      currentIndex -= 1;
      updateStories();
    }
  });

  storyNextButton.addEventListener("click", () => {
    if (currentIndex < maxIndex) {
      currentIndex += 1;
      updateStories();
    }
  });

  window.addEventListener("resize", updateStories);
  updateStories();
}

// Expands extra hashtags in post captions once per click.
const captionMoreButtons = document.querySelectorAll(".caption-more");

captionMoreButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const caption = button.closest(".post-caption");
    const hashtags = caption?.querySelector(".caption-hashtags");
    if (!hashtags) return;

    hashtags.classList.toggle("visible");
    button.style.display = "none";
  });
});

const likeButtons = document.querySelectorAll(".like-button");

likeButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const countElement = button.querySelector(".like-count");
    if (!countElement) return;

    const animateLike = () => {
      // Force reflow so the pop animation can replay on rapid toggles.
      button.classList.remove("like-animate");
      void button.offsetWidth;
      button.classList.add("like-animate");
    };

    const currentCount = parseInt(countElement.textContent, 10);
    const safeCount = Number.isNaN(currentCount) ? 0 : currentCount;

    if (button.classList.contains("liked")) {
      countElement.textContent = `${Math.max(0, safeCount - 1)}`;
      button.classList.remove("liked");
      animateLike();
      return;
    }

    countElement.textContent = `${safeCount + 1}`;
    button.classList.add("liked");
    animateLike();
  });
});

// =============================
// Post Carousels
// =============================
const postCarousels = document.querySelectorAll(".post-carousel");

postCarousels.forEach((carousel) => {
  const track = carousel.querySelector(".post-carousel-track");
  const items = carousel.querySelectorAll(".post-carousel-track .post-img");
  const prevButton = carousel.querySelector(".post-carousel-prev");
  const nextButton = carousel.querySelector(".post-carousel-next");

  if (!track || !items.length || !prevButton || !nextButton) return;

  let currentSlide = 0;
  const maxSlide = items.length - 1;

  const dotsContainer = document.createElement("div");
  dotsContainer.className = "post-carousel-dots";
  const dots = [];

  items.forEach((_, index) => {
    const dot = document.createElement("button");
    dot.type = "button";
    dot.className = "post-carousel-dot";
    dot.setAttribute("aria-label", `Ir para imagem ${index + 1}`);

    dot.addEventListener("click", () => {
      currentSlide = index;
      updateCarousel();
    });

    dots.push(dot);
    dotsContainer.appendChild(dot);
  });

  carousel.appendChild(dotsContainer);

  const updateCarousel = () => {
    // Translate by container width, so each step always matches one slide.
    const slideWidth = carousel.clientWidth;
    track.style.transform = `translateX(-${currentSlide * slideWidth}px)`;

    const hasPrevious = currentSlide > 0;
    const hasNext = currentSlide < maxSlide;

    prevButton.style.display = hasPrevious ? "flex" : "none";
    nextButton.style.display = hasNext ? "flex" : "none";

    prevButton.disabled = !hasPrevious;
    nextButton.disabled = !hasNext;

    dots.forEach((dot, index) => {
      dot.classList.toggle("active", index === currentSlide);
    });
  };

  prevButton.addEventListener("click", () => {
    if (currentSlide > 0) {
      currentSlide -= 1;
      updateCarousel();
    }
  });

  nextButton.addEventListener("click", () => {
    if (currentSlide < maxSlide) {
      currentSlide += 1;
      updateCarousel();
    }
  });

  window.addEventListener("resize", updateCarousel);
  updateCarousel();
});

// =============================
// Video Posts (Play/Pause + Mute)
// =============================
const postVideoWrappers = document.querySelectorAll(".post-video-wrapper");

postVideoWrappers.forEach((wrapper) => {
  const video = wrapper.querySelector("video");
  const volumeToggle = wrapper.querySelector(".video-volume-toggle");

  if (!video || !volumeToggle) return;

  const syncVolumeState = () => {
    const isUnmuted = !video.muted;
    volumeToggle.classList.toggle("is-unmuted", isUnmuted);
    volumeToggle.setAttribute(
      "aria-label",
      isUnmuted ? "Silenciar" : "Ativar som",
    );
  };

  wrapper.addEventListener("click", () => {
    if (video.paused) {
      video.play();
    } else {
      video.pause();
    }
  });

  volumeToggle.addEventListener("click", (event) => {
    event.stopPropagation();
    video.muted = !video.muted;
    syncVolumeState();
  });

  syncVolumeState();
});

// =============================
// Story Viewer
// =============================
const firstStoryCard = document.querySelector(".story-card.first-story");
const storyViewerFooter = document.querySelector(".story-viewer-footer");
const storyViewerReplyInput = document.querySelector(".story-viewer-reply");
const storyViewerSendText = document.querySelector(".story-viewer-send-text");
const quickEmojiButtons = document.querySelectorAll(".quick-emoji-btn");
const storyReactionToast = document.getElementById("storyReactionToast");
const storyViewerOverlay = document.getElementById("storyViewerOverlay");
const storyViewerClose = document.getElementById("storyViewerClose");
const storyViewerPlayPause = document.getElementById("storyViewerPlayPause");
const storyViewerLikeButton = document.getElementById("storyViewerLikeButton");
const storyViewerProgressBar = document.querySelector(
  ".story-viewer-progress > span",
);

if (storyViewerLikeButton) {
  storyViewerLikeButton.addEventListener("click", () => {
    // Re-run heart animation every time user toggles like.
    storyViewerLikeButton.classList.toggle("liked");
    storyViewerLikeButton.classList.remove("like-animate");
    void storyViewerLikeButton.offsetWidth;
    storyViewerLikeButton.classList.add("like-animate");
  });
}

if (
  quickEmojiButtons.length &&
  storyViewerReplyInput &&
  storyReactionToast &&
  storyViewerSendText
) {
  let reactionToastTimeoutId = null;

  const showReactionToast = () => {
    // Reset timer before showing again to avoid stacked hide transitions.
    storyReactionToast.classList.add("visible");

    if (reactionToastTimeoutId) {
      window.clearTimeout(reactionToastTimeoutId);
    }

    reactionToastTimeoutId = window.setTimeout(() => {
      storyReactionToast.classList.remove("visible");
      reactionToastTimeoutId = null;
    }, 2000);
  };

  quickEmojiButtons.forEach((button) => {
    button.addEventListener("mousedown", (event) => {
      // Keep input focus until click handler runs, then blur manually.
      event.preventDefault();
    });

    button.addEventListener("click", () => {
      storyViewerReplyInput.blur();
      showReactionToast();
    });
  });

  storyViewerSendText.addEventListener("click", () => {
    storyViewerReplyInput.value = "";
    storyViewerReplyInput.dispatchEvent(new Event("input", { bubbles: true }));
    storyViewerReplyInput.blur();
    showReactionToast();
  });

  storyViewerSendText.addEventListener("mousedown", (event) => {
    // Avoid losing focus before click executes.
    event.preventDefault();
  });
}

if (
  firstStoryCard &&
  storyViewerFooter &&
  storyViewerReplyInput &&
  storyViewerOverlay &&
  storyViewerClose &&
  storyViewerPlayPause &&
  storyViewerProgressBar
) {
  const storyDurationMs = 15000;
  // Story progress state machine.
  let storyAnimationFrameId = null;
  let storyStartTimestamp = 0;
  let storyElapsedBeforePause = 0;
  let storyElapsedCurrent = 0;
  let storyPaused = false;
  let pausedByInputFocus = false;

  const updateInputTextState = () => {
    const hasText = storyViewerReplyInput.value.length > 0;
    storyViewerFooter.classList.toggle("has-text", hasText);
  };

  const setPlayPauseState = (paused) => {
    storyPaused = paused;
    storyViewerPlayPause.classList.toggle("is-paused", paused);
    storyViewerPlayPause.setAttribute(
      "aria-label",
      paused ? "Continuar story" : "Pausar story",
    );
  };

  const updateStoryProgressByElapsed = (elapsedMs) => {
    const progress = Math.min(1, elapsedMs / storyDurationMs);
    storyViewerProgressBar.style.width = `${progress * 100}%`;
  };

  const stopStoryProgress = () => {
    if (storyAnimationFrameId !== null) {
      window.cancelAnimationFrame(storyAnimationFrameId);
      storyAnimationFrameId = null;
    }
  };

  const resetStoryProgress = () => {
    storyElapsedBeforePause = 0;
    storyElapsedCurrent = 0;
    storyViewerProgressBar.style.width = "0%";
    setPlayPauseState(false);
  };

  const closeStoryViewer = () => {
    // Cleanup all state here to avoid stale classes when reopening.
    stopStoryProgress();
    storyStartTimestamp = 0;
    pausedByInputFocus = false;
    storyViewerFooter.classList.remove("input-active");
    storyViewerFooter.classList.remove("has-text");
    resetStoryProgress();

    storyViewerOverlay.classList.remove("open");
    storyViewerOverlay.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  };

  const tickStoryProgress = (timestamp) => {
    // requestAnimationFrame loop: computes elapsed time and closes at 100%.
    if (!storyViewerOverlay.classList.contains("open")) {
      stopStoryProgress();
      return;
    }

    if (storyPaused) {
      stopStoryProgress();
      return;
    }

    if (!storyStartTimestamp) {
      storyStartTimestamp = timestamp;
    }

    const elapsed = storyElapsedBeforePause + (timestamp - storyStartTimestamp);
    storyElapsedCurrent = elapsed;

    updateStoryProgressByElapsed(elapsed);

    if (elapsed >= storyDurationMs) {
      closeStoryViewer();
      return;
    }

    storyAnimationFrameId = window.requestAnimationFrame(tickStoryProgress);
  };

  const startStoryProgress = () => {
    stopStoryProgress();
    storyStartTimestamp = 0;
    resetStoryProgress();
    setPlayPauseState(false);
    storyAnimationFrameId = window.requestAnimationFrame(tickStoryProgress);
  };

  const toggleStoryPause = () => {
    if (!storyViewerOverlay.classList.contains("open")) return;

    if (storyPaused) {
      // Resume: restart timestamp baseline while preserving elapsed time.
      storyStartTimestamp = 0;
      setPlayPauseState(false);
      storyAnimationFrameId = window.requestAnimationFrame(tickStoryProgress);
      return;
    }

    storyElapsedBeforePause = storyElapsedCurrent;
    setPlayPauseState(true);
    stopStoryProgress();
    updateStoryProgressByElapsed(storyElapsedBeforePause);
  };

  const openStoryViewer = () => {
    // Lock page scroll while overlay is open.
    storyViewerOverlay.classList.add("open");
    storyViewerOverlay.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
    pausedByInputFocus = false;
    storyViewerFooter.classList.remove("input-active");
    updateInputTextState();
    startStoryProgress();
  };

  storyViewerReplyInput.addEventListener("focus", () => {
    if (!storyViewerOverlay.classList.contains("open")) return;

    storyViewerFooter.classList.add("input-active");
    updateInputTextState();

    if (!storyPaused) {
      pausedByInputFocus = true;
      toggleStoryPause();
      return;
    }

    pausedByInputFocus = false;
  });

  storyViewerReplyInput.addEventListener("blur", () => {
    if (!storyViewerOverlay.classList.contains("open")) return;

    storyViewerFooter.classList.remove("input-active");
    updateInputTextState();

    if (pausedByInputFocus && storyPaused) {
      toggleStoryPause();
    }

    pausedByInputFocus = false;
  });

  storyViewerReplyInput.addEventListener("input", updateInputTextState);

  firstStoryCard.addEventListener("click", openStoryViewer);

  firstStoryCard.addEventListener("keydown", (event) => {
    if (event.key === "Enter" || event.key === " ") {
      event.preventDefault();
      openStoryViewer();
    }
  });

  storyViewerClose.addEventListener("click", closeStoryViewer);
  storyViewerPlayPause.addEventListener("click", toggleStoryPause);

  storyViewerOverlay.addEventListener("click", (event) => {
    if (event.target === storyViewerOverlay) {
      closeStoryViewer();
    }
  });

  document.addEventListener("keydown", (event) => {
    if (
      event.key === "Escape" &&
      storyViewerOverlay.classList.contains("open")
    ) {
      closeStoryViewer();
    }
  });
}
