import React from "react";

export default {
  title: "ProEvent/Hero CTA",
};

export const Basic = () => (
  <section
    className="proevent-hero-cta relative overflow-hidden rounded-xl px-6 py-10 md:px-10 md:py-16 bg-slate-900 text-white"
  >
    <div className="relative max-w-xl">
      <h2 className="text-3xl md:text-4xl font-bold mb-4">
        Upcoming tech & design events
      </h2>
      <p className="text-sm md:text-base text-slate-200 mb-6">
        Discover what’s happening next week and save your seat before tickets run out.
      </p>
      <a
        href="#"
        className="inline-flex items-center px-5 py-3 rounded-md bg-primary hover:bg-primary/80 text-sm font-semibold"
      >
        Browse events
      </a>
    </div>
  </section>
);
