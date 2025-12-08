import { gsap } from "gsap";

function animateSplitText(
    selector,
    duration = 1,
    shouldFloat = false,
    staggerValue = 0.05
) {
    const textElement = document.querySelector(selector);

    if (textElement) {
        const chars = [];

        function splitNode(node) {
            const childNodes = Array.from(node.childNodes);
            node.innerHTML = "";

            childNodes.forEach((child) => {
                if (child.nodeType === 3) {
                    const text = child.textContent;
                    const segments = text.split(/(\s+)/);

                    segments.forEach((segment) => {
                        if (!segment) return;

                        if (segment.match(/^\s+$/)) {
                            node.appendChild(document.createTextNode(segment));
                        } else {
                            const wordDiv = document.createElement("div");
                            wordDiv.style.display = "inline-block";

                            segment.split("").forEach((char) => {
                                const charSpan = document.createElement("span");
                                charSpan.innerText = char;
                                charSpan.style.display = "inline-block";
                                charSpan.classList.add("char");
                                wordDiv.appendChild(charSpan);
                                chars.push(charSpan);
                            });

                            node.appendChild(wordDiv);
                        }
                    });
                } else if (child.nodeType === 1) {
                    splitNode(child);
                    node.appendChild(child);
                }
            });
        }

        splitNode(textElement);

        gsap.from(chars, {
            duration: duration,
            opacity: 0,
            y: 50,
            stagger: staggerValue,
            ease: "back.out(1.7)",
            onComplete: () => {
                if (shouldFloat) {
                    startWaveEffect(chars);
                }
            },
        });
    }
}

function startWaveEffect(targets) {
    gsap.to(targets, {
        y: -10,
        duration: 1.5,
        ease: "sine.inOut",
        yoyo: true,
        repeat: -1,
        stagger: {
            each: 0.1,
            from: "start",
        },
    });
}

animateSplitText(".animate-title", 1, true, 0.05);
animateSplitText(".animate-text", 1, false, 0.01);
