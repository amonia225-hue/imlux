import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

/// Charte « Or & Émeraude » du logo PROJET IM'LUX.
class AppColors {
  // Or
  static const gold = Color(0xFFC8A24A);
  static const goldLight = Color(0xFFE8CE7E);
  static const goldDark = Color(0xFF9C7B2E);

  // Émeraude
  static const emerald = Color(0xFF2E8B5F);
  static const emeraldDeep = Color(0xFF1F6B4C);
  static const emeraldDark = Color(0xFF123D2C);

  // Fonds & surfaces (dark luxe)
  static const bg = Color(0xFF0E1A14);
  static const surface = Color(0xFF15241B);
  static const surface2 = Color(0xFF1D3327);
  static const border = Color(0x33C8A24A); // or translucide

  // Texte
  static const ivory = Color(0xFFF3ECDB);
  static const sage = Color(0xFFA9B7A6);

  // États
  static const success = Color(0xFF46B07C);
  static const warning = Color(0xFFE8C15A);
  static const danger = Color(0xFFE07A6B);

  static const goldGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [goldLight, gold, goldDark],
  );

  static const brandGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [goldLight, gold, emerald],
  );
}

class AppTheme {
  static ThemeData get dark {
    final base = ThemeData.dark(useMaterial3: true);
    final textTheme = GoogleFonts.interTextTheme(base.textTheme).apply(
      bodyColor: AppColors.ivory,
      displayColor: AppColors.ivory,
    );

    return base.copyWith(
      scaffoldBackgroundColor: AppColors.bg,
      colorScheme: const ColorScheme.dark(
        primary: AppColors.gold,
        secondary: AppColors.emerald,
        surface: AppColors.surface,
        error: AppColors.danger,
        onPrimary: Color(0xFF1A1206),
        onSurface: AppColors.ivory,
      ),
      textTheme: textTheme,
      appBarTheme: AppBarTheme(
        backgroundColor: Colors.transparent,
        elevation: 0,
        centerTitle: false,
        titleTextStyle: GoogleFonts.cormorantGaramond(
          fontSize: 26,
          fontWeight: FontWeight.w700,
          color: AppColors.ivory,
        ),
        iconTheme: const IconThemeData(color: AppColors.gold),
      ),
      cardTheme: CardThemeData(
        color: AppColors.surface,
        elevation: 0,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20),
          side: const BorderSide(color: AppColors.border),
        ),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: AppColors.surface2,
        hintStyle: const TextStyle(color: AppColors.sage),
        labelStyle: const TextStyle(color: AppColors.sage),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: AppColors.border),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: AppColors.gold, width: 1.5),
        ),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: AppColors.border),
        ),
      ),
      dividerColor: AppColors.border,
    );
  }

  /// Style de titre serif élégant (rappel du logo).
  static TextStyle display(double size, [FontWeight weight = FontWeight.w700]) =>
      GoogleFonts.cormorantGaramond(
        fontSize: size,
        fontWeight: weight,
        color: AppColors.ivory,
        height: 1.1,
      );
}
