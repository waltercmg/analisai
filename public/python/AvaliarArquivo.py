# encoding=utf8  
# coding=UTF-8

import zipfile, os, datetime, json
from os import listdir
from datetime import date

import Constantes, Util

dados = dict()
todosLabels = []
imprimirLog = False 
lotacoes = []

def main():
    global dados
    p("Início do processamento...")
    if getArquivoZip():
        getArquivosCsv()
    p("Fim do processamento...")
    #imprimeDados()
    gerarRelatorios()
    print json.dumps(lotacoes)

def descompactar(arquivo):
    zip_ref = zipfile.ZipFile(Constantes.pastaArquivoZip+arquivo, 'r')
    zip_ref.extractall(Constantes.pastaArquivoCsv)
    zip_ref.close()

def getArquivoZip():
    retorno = False
    for f in listdir(Constantes.pastaArquivoZip):
        if f[-3:].lower() == "zip":
            p("Descompactando arquivo: " + f)
            descompactar(f)
            moverArquivo(f, Constantes.pastaArquivoZip, Constantes.pastaArquivoZipProc)
            retorno = True 
            break           
    return retorno

def getArquivosCsv():
    p("Buscando arquivos CSV")    
    for f in listdir(Constantes.pastaArquivoCsv):
        if f[-3:].lower() == "csv":
            p("Tratando arquivo: " + f)
            tratarCsv(f)
            moverArquivo(f, Constantes.pastaArquivoCsv, Constantes.pastaArquivoCsvProc)

def tratarCsv(arquivo):
    global dados
    global todosLabels
    f = open(Constantes.pastaArquivoCsv+arquivo, 'r')
    labels = quebrarLinha(f.readline())
    for linha in f:                      
        addDados(arquivo.split(Constantes.sepNmArq)[0], labels, linha)
    f.close()

def addDados(indicador, labels, linha):
    global dados
    array = quebrarLinha(linha)
    if not dados.get(array[0]):
        dados.update({array[0]:{}})
    if not dados.get(array[0]).get(indicador):
        dados.get(array[0]).update({indicador:{}})    
    if not dados.get(array[0]).get(indicador).get(array[1]):
        dados.get(array[0]).get(indicador).update({array[1]:{}})
    cont = 2
    for coluna in array[2:]:
        dados.get(array[0]).get(indicador).get(array[1]).update({labels[cont]:coluna})
        cont += 1

def quebrarLinha(linha):
    array = linha.replace("|ALM","").replace("\"","").split(Constantes.sep)
    return array[:-1]
                       
def moverArquivo(arquivo, pastaOrigem, pastaDestino):
    os.rename(pastaOrigem+arquivo, pastaDestino+gerarNmArq()+"."+arquivo[-3:])    

def gerarNmArq():
    nmArq = datetime.datetime.today().strftime('%Y-%m-%d-%H-%M-%S')
    return nmArq


def imprimeDados():
    for indicador, resps in dados.iteritems():
        p(">>"+indicador)
        for resp, meses in resps.iteritems():
            p(" >>"+resp)
            for mes, infos in meses.iteritems():
                p("  >>"+mes)
                for info, valor in infos.iteritems():
                    p("   >>"+info+": "+valor)


def p(texto):
    if imprimirLog:
        print texto

main()
