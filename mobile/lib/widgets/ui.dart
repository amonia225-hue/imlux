import 'package:flutter/material.dart';
import '../theme/app_theme.dart';

/// Logo IM'LUX présenté sur une plaque ivoire (le logo a un fond blanc).
class LogoBadge extends StatelessWidget {
  final double size;
  const LogoBadge({super.key, this.size = 130});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(10),
      decoration: BoxDecoration(
        color: const Color(0xFFFBF7EE),
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: AppColors.gold.withValues(alpha: 0.25),
            blurRadius: 30,
            spreadRadius: 2,
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(16),
        child: Image.asset('assets/logo.jpeg',
            width: size, height: size, fit: BoxFit.cover),
      ),
    );
  }
}

/// Bouton principal au dégradé or.
class GoldButton extends StatelessWidget {
  final String label;
  final VoidCallback? onPressed;
  final bool loading;
  const GoldButton({
    super.key,
    required this.label,
    this.onPressed,
    this.loading = false,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: loading ? null : onPressed,
      child: Container(
        height: 54,
        alignment: Alignment.center,
        decoration: BoxDecoration(
          gradient: AppColors.goldGradient,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: AppColors.gold.withValues(alpha: 0.35),
              blurRadius: 18,
              offset: const Offset(0, 8),
            ),
          ],
        ),
        child: loading
            ? const SizedBox(
                width: 22,
                height: 22,
                child: CircularProgressIndicator(
                    strokeWidth: 2.4, color: Color(0xFF1A1206)),
              )
            : Text(
                label,
                style: const TextStyle(
                  color: Color(0xFF1A1206),
                  fontWeight: FontWeight.w800,
                  fontSize: 16,
                  letterSpacing: .3,
                ),
              ),
      ),
    );
  }
}

class GradientProgressBar extends StatelessWidget {
  final double percent; // 0..100
  final double height;
  const GradientProgressBar({super.key, required this.percent, this.height = 10});

  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: BorderRadius.circular(999),
      child: Stack(
        children: [
          Container(height: height, color: AppColors.surface2),
          FractionallySizedBox(
            widthFactor: (percent.clamp(0, 100)) / 100,
            child: Container(
              height: height,
              decoration: const BoxDecoration(gradient: AppColors.goldGradient),
            ),
          ),
        ],
      ),
    );
  }
}

class StatusChip extends StatelessWidget {
  final String label;
  final String status; // termine/solde, en_cours, a_venir/annule
  const StatusChip({super.key, required this.label, required this.status});

  @override
  Widget build(BuildContext context) {
    final (bg, fg) = switch (status) {
      'termine' || 'solde' => (
          AppColors.success.withValues(alpha: 0.15),
          AppColors.success
        ),
      'en_cours' => (AppColors.warning.withValues(alpha: 0.15), AppColors.warning),
      'annule' => (AppColors.danger.withValues(alpha: 0.15), AppColors.danger),
      _ => (AppColors.sage.withValues(alpha: 0.15), AppColors.sage),
    };
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(label,
          style: TextStyle(
              color: fg, fontWeight: FontWeight.w700, fontSize: 12)),
    );
  }
}

class SectionCard extends StatelessWidget {
  final Widget child;
  final EdgeInsetsGeometry padding;
  const SectionCard({
    super.key,
    required this.child,
    this.padding = const EdgeInsets.all(16),
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: padding,
      decoration: BoxDecoration(
        color: AppColors.surface,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: AppColors.border),
      ),
      child: child,
    );
  }
}
