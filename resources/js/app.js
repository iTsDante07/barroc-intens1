import { gsap } from "gsap";

function animateSplitText(selector, duration = 1) {
    const textElement = document.querySelector(selector);

    if (textElement) {
        const chars = [];

        // Recursive function to split text nodes while preserving HTML structure
        function splitNode(node) {
            const childNodes = Array.from(node.childNodes);
            node.innerHTML = ""; // Clear content to rebuild

            childNodes.forEach((child) => {
                if (child.nodeType === 3) {
                    // Text node
                    const text = child.textContent;
                    // Split by whitespace but capture it to preserve spacing
                    const segments = text.split(/(\s+)/);

                    segments.forEach((segment) => {
                        if (!segment) return;

                        if (segment.match(/^\s+$/)) {
                            // Preserve whitespace
                            node.appendChild(document.createTextNode(segment));
                        } else {
                            // Wrap word in a div to keep characters together
                            const wordDiv = document.createElement("div");
                            wordDiv.style.display = "inline-block";

                            segment.split("").forEach((char) => {
                                const charSpan = document.createElement("span");
                                charSpan.innerText = char;
                                charSpan.style.display = "inline-block";
                                wordDiv.appendChild(charSpan);
                                chars.push(charSpan);
                            });

                            node.appendChild(wordDiv);
                        }
                    });
                } else if (child.nodeType === 1) {
                    // Element node (e.g., span)
                    splitNode(child); // Recurse into the element
                    node.appendChild(child); // Put the element back
                }
            });
        }

        splitNode(textElement);

        gsap.from(chars, {
            duration: duration,
            opacity: 0,
            y: 20,
            stagger: 0.01,
            ease: "power2.out",
        });
    }
}

animateSplitText(".animate-title", 2);
animateSplitText(".animate-text", 0.1);
