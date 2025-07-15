// Import GSAP if not already imported
import { gsap } from "gsap";

// Wait for DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function () {
    let xPos = 0;

    // Initialize the gallery
    function initGallery() {
        gsap.timeline()
            .set(".ring", { rotationY: 180, cursor: "grab" })
            .set(".img", {
                rotateY: (i) => i * -36,
                transformOrigin: "50% 50% 500px",
                z: -500,
                backgroundImage: (i) =>
                    `url(https://picsum.photos/id/${i + 32}/600/400/)`,
                backgroundPosition: (i) => getBgPos(i),
                backfaceVisibility: "hidden",
            })
            .from(".img", {
                duration: 1.5,
                y: 200,
                opacity: 0,
                stagger: 0.1,
                ease: "expo",
            })
            .add(() => {
                document.querySelectorAll(".img").forEach((img) => {
                    img.addEventListener("mouseenter", (e) => {
                        gsap.to(".img", {
                            opacity: (i, t) =>
                                t === e.currentTarget ? 1 : 0.5,
                            ease: "power3",
                        });
                    });

                    img.addEventListener("mouseleave", () => {
                        gsap.to(".img", { opacity: 1, ease: "power2.inOut" });
                    });
                });
            }, "-=0.5");

        // Event listeners for drag functionality
        window.addEventListener("mousedown", dragStart);
        window.addEventListener("touchstart", dragStart);
        window.addEventListener("mouseup", dragEnd);
        window.addEventListener("touchend", dragEnd);
    }

    // Drag functions
    function dragStart(e) {
        if (e.touches) e.clientX = e.touches[0].clientX;
        xPos = Math.round(e.clientX);
        gsap.set(".ring", { cursor: "grabbing" });
        window.addEventListener("mousemove", drag);
        window.addEventListener("touchmove", drag);
    }

    function drag(e) {
        if (e.touches) e.clientX = e.touches[0].clientX;

        gsap.to(".ring", {
            rotationY: "-=" + ((Math.round(e.clientX) - xPos) % 360),
            onUpdate: () => {
                document.querySelectorAll(".img").forEach((img, i) => {
                    img.style.backgroundPosition = getBgPos(i);
                });
            },
        });

        xPos = Math.round(e.clientX);
    }

    function dragEnd() {
        window.removeEventListener("mousemove", drag);
        window.removeEventListener("touchmove", drag);
        gsap.set(".ring", { cursor: "grab" });
    }

    function getBgPos(i) {
        const rotationY = gsap.getProperty(".ring", "rotationY");
        return (
            100 - (((rotationY - 180 - i * 36) % 360) / 360) * 500 + "px 0px"
        );
    }

    // Initialize the gallery
    initGallery();
});
