import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';

import '../models/souscription.dart';
import '../services/api_service.dart';
import '../theme/app_theme.dart';
import '../utils/format.dart';
import '../widgets/ui.dart';

class SouscriptionDetailScreen extends StatefulWidget {
  final int souscriptionId;
  const SouscriptionDetailScreen({super.key, required this.souscriptionId});

  @override
  State<SouscriptionDetailScreen> createState() =>
      _SouscriptionDetailScreenState();
}

class _SouscriptionDetailScreenState extends State<SouscriptionDetailScreen> {
  late Future<Souscription> _future;

  @override
  void initState() {
    super.initState();
    _future = ApiService.souscription(widget.souscriptionId);
  }

  void _viewImage(String url) {
    showDialog(
      context: context,
      barrierColor: Colors.black.withValues(alpha: 0.9),
      builder: (_) => GestureDetector(
        onTap: () => Navigator.pop(context),
        child: InteractiveViewer(
          child: Center(
            child: CachedNetworkImage(imageUrl: url, fit: BoxFit.contain),
          ),
        ),
      ),
    );
  }

  Future<void> _open(String? url) async {
    if (url == null || url.isEmpty) return;
    final uri = Uri.parse(url);
    if (!await launchUrl(uri, mode: LaunchMode.externalApplication)) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Impossible d\'ouvrir le document.')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: FutureBuilder<Souscription>(
        future: _future,
        builder: (context, snap) {
          if (snap.connectionState == ConnectionState.waiting) {
            return const Center(
                child: CircularProgressIndicator(color: AppColors.gold));
          }
          if (snap.hasError || !snap.hasData) {
            return SafeArea(
              child: Column(
                children: [
                  _miniBar(context),
                  const Expanded(
                    child: Center(
                      child: Text('Erreur de chargement.',
                          style: TextStyle(color: AppColors.sage)),
                    ),
                  ),
                ],
              ),
            );
          }

          final s = snap.data!;
          return DefaultTabController(
            length: 3,
            child: SafeArea(
              child: Column(
                children: [
                  _header(context, s),
                  const TabBar(
                    labelColor: AppColors.gold,
                    unselectedLabelColor: AppColors.sage,
                    indicatorColor: AppColors.gold,
                    tabs: [
                      Tab(text: 'Versements'),
                      Tab(text: 'Travaux'),
                      Tab(text: 'Infos'),
                    ],
                  ),
                  Expanded(
                    child: TabBarView(
                      children: [
                        _versementsTab(s),
                        _travauxTab(s),
                        _infosTab(s),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }

  Widget _miniBar(BuildContext context) => Align(
        alignment: Alignment.centerLeft,
        child: IconButton(
          icon: const Icon(Icons.arrow_back, color: AppColors.gold),
          onPressed: () => Navigator.pop(context),
        ),
      );

  Widget _header(BuildContext context, Souscription s) {
    return Container(
      padding: const EdgeInsets.fromLTRB(8, 4, 16, 16),
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [AppColors.emeraldDeep, AppColors.emeraldDark],
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              IconButton(
                icon: const Icon(Icons.arrow_back, color: AppColors.gold),
                onPressed: () => Navigator.pop(context),
              ),
              Expanded(
                child: Text(s.programme.name,
                    style: AppTheme.display(24),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis),
              ),
              StatusChip(label: s.statusLabel, status: s.status),
            ],
          ),
          Padding(
            padding: const EdgeInsets.only(left: 12),
            child: Text(
              'Lot ${s.lot.reference} · ${s.lot.typeLogement}'
              '${s.lot.surface != null ? ' · ${s.lot.surface!.round()} m²' : ''}',
              style: const TextStyle(color: AppColors.sage, fontSize: 13),
            ),
          ),
          const SizedBox(height: 16),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 12),
            child: Column(
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('Progression du paiement',
                        style: TextStyle(color: AppColors.ivory, fontSize: 13)),
                    Text('${s.progressPercent.round()}%',
                        style: const TextStyle(
                            color: AppColors.gold,
                            fontWeight: FontWeight.w800)),
                  ],
                ),
                const SizedBox(height: 8),
                GradientProgressBar(percent: s.progressPercent, height: 10),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // ===== Onglet Versements =====
  Widget _versementsTab(Souscription s) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        GoldButton(
          label: 'Télécharger mon attestation',
          onPressed: () => _open(s.attestationUrl),
        ),
        const SizedBox(height: 20),
        Text('Historique (${s.versements.length})',
            style: AppTheme.display(20, FontWeight.w600)),
        const SizedBox(height: 12),
        if (s.versements.isEmpty)
          const SectionCard(
            child: Text('Aucun versement enregistré pour le moment.',
                style: TextStyle(color: AppColors.sage)),
          )
        else
          ...s.versements.map((v) => Padding(
                padding: const EdgeInsets.only(bottom: 12),
                child: SectionCard(
                  padding: const EdgeInsets.all(14),
                  child: Row(
                    children: [
                      Container(
                        width: 44,
                        height: 44,
                        decoration: BoxDecoration(
                          color: AppColors.success.withValues(alpha: 0.12),
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: const Icon(Icons.payments_outlined,
                            color: AppColors.success),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(fcfa(v.amount),
                                style: const TextStyle(
                                    color: AppColors.ivory,
                                    fontWeight: FontWeight.w800,
                                    fontSize: 15)),
                            const SizedBox(height: 2),
                            Text(
                              '${shortDate(v.paymentDate)} · ${v.paymentMethodLabel}',
                              style: const TextStyle(
                                  color: AppColors.sage, fontSize: 12),
                            ),
                          ],
                        ),
                      ),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.end,
                        children: [
                          TextButton(
                            onPressed: () => _open(v.factureUrl),
                            child: const Text('Facture',
                                style: TextStyle(color: AppColors.gold)),
                          ),
                          if (v.recuUrl != null)
                            TextButton(
                              onPressed: () => _open(v.recuUrl!),
                              child: const Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Icon(Icons.receipt_long_outlined,
                                      size: 16, color: AppColors.success),
                                  SizedBox(width: 4),
                                  Text('Reçu',
                                      style: TextStyle(color: AppColors.success)),
                                ],
                              ),
                            ),
                        ],
                      ),
                    ],
                  ),
                ),
              )),
      ],
    );
  }

  // ===== Onglet Travaux =====
  Widget _travauxTab(Souscription s) {
    final travaux = s.travaux;
    if (travaux == null || travaux.etapes.isEmpty) {
      return const Center(
        child: Padding(
          padding: EdgeInsets.all(32),
          child: Text('Le suivi du chantier sera bientôt disponible.',
              textAlign: TextAlign.center,
              style: TextStyle(color: AppColors.sage)),
        ),
      );
    }

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        SectionCard(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text('Avancement global',
                      style: AppTheme.display(20, FontWeight.w600)),
                  Text('${travaux.avancementGlobal}%',
                      style: const TextStyle(
                          color: AppColors.gold,
                          fontWeight: FontWeight.w800,
                          fontSize: 18)),
                ],
              ),
              const SizedBox(height: 12),
              GradientProgressBar(
                  percent: travaux.avancementGlobal.toDouble(), height: 12),
            ],
          ),
        ),
        const SizedBox(height: 16),
        ...travaux.etapes.map(_etapeTile),
      ],
    );
  }

  Widget _etapeTile(Etape e) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 14),
      child: SectionCard(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (e.images.isNotEmpty) ...[
              SizedBox(
                height: 140,
                child: ListView.separated(
                  scrollDirection: Axis.horizontal,
                  itemCount: e.images.length,
                  separatorBuilder: (_, _) => const SizedBox(width: 8),
                  itemBuilder: (_, i) => GestureDetector(
                    onTap: () => _viewImage(e.images[i]),
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(12),
                      child: CachedNetworkImage(
                        imageUrl: e.images[i],
                        width: 190,
                        height: 140,
                        fit: BoxFit.cover,
                        placeholder: (_, _) =>
                            Container(width: 190, color: AppColors.surface2),
                        errorWidget: (_, _, _) => Container(
                          width: 190,
                          color: AppColors.surface2,
                          child: const Icon(Icons.image_not_supported,
                              color: AppColors.sage),
                        ),
                      ),
                    ),
                  ),
                ),
              ),
              const SizedBox(height: 4),
              Text('${e.images.length} photo(s) · touchez pour agrandir',
                  style: const TextStyle(color: AppColors.sage, fontSize: 11)),
              const SizedBox(height: 10),
            ],
            Row(
              children: [
                Expanded(
                  child: Text(e.title,
                      style: const TextStyle(
                          color: AppColors.ivory,
                          fontWeight: FontWeight.w700,
                          fontSize: 16)),
                ),
                StatusChip(label: e.statusLabel, status: e.status),
              ],
            ),
            if (e.description != null && e.description!.isNotEmpty) ...[
              const SizedBox(height: 6),
              Text(e.description!,
                  style: const TextStyle(color: AppColors.sage, fontSize: 13)),
            ],
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(child: GradientProgressBar(percent: e.progress.toDouble(), height: 8)),
                const SizedBox(width: 10),
                Text('${e.progress}%',
                    style: const TextStyle(
                        color: AppColors.gold,
                        fontWeight: FontWeight.w700,
                        fontSize: 13)),
              ],
            ),
            if (e.dateRealisee != null || e.datePrevue != null) ...[
              const SizedBox(height: 8),
              Text(
                e.dateRealisee != null
                    ? 'Réalisée le ${shortDate(e.dateRealisee)}'
                    : 'Prévue le ${shortDate(e.datePrevue)}',
                style: const TextStyle(color: AppColors.sage, fontSize: 12),
              ),
            ],
          ],
        ),
      ),
    );
  }

  // ===== Onglet Infos =====
  Widget _infosTab(Souscription s) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        SectionCard(
          child: Column(
            children: [
              _infoRow('Prix total', fcfa(s.totalPrice)),
              _divider(),
              _infoRow('Total versé', fcfa(s.totalVerse),
                  color: AppColors.success),
              _divider(),
              _infoRow('Reste à payer', fcfa(s.resteAPayer),
                  color: s.resteAPayer > 0 ? AppColors.gold : AppColors.success),
              _divider(),
              _infoRow(s.echeanceLabel, fcfa(s.mensualite)),
              _divider(),
              _infoRow('Rythme de règlement', s.rythmeLabel),
              _divider(),
              _infoRow('Nombre d\'échéances', '${s.nbMensualites}'),
              _divider(),
              if (s.status != 'solde') ...[
                _infoRow('Échéances restantes', '${s.echeancesRestantes}'),
                _divider(),
                _infoRow('Échéance recalculée', fcfa(s.echeanceActuelle),
                    color: AppColors.gold),
                _divider(),
              ],
              _infoRow("Date d'adhésion", shortDate(s.dateSouscription)),
              _divider(),
              _infoRow('Localisation', s.programme.location),
            ],
          ),
        ),
      ],
    );
  }

  Widget _infoRow(String label, String value, {Color color = AppColors.ivory}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(color: AppColors.sage)),
          Flexible(
            child: Text(value,
                textAlign: TextAlign.right,
                style: TextStyle(color: color, fontWeight: FontWeight.w700)),
          ),
        ],
      ),
    );
  }

  Widget _divider() => const Divider(color: AppColors.border, height: 1);
}
